
var storage = window.localStorage;
var admpath = $('meta[name="admin-path"]').attr('content');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    }
});

function otoast(status, result){
    var option = {
        text: result.message,
        buttons: {
            sticker: false
        },
    }
    switch(status){
        case 'error':
            option['addclass'] = 'bg-danger';
            option['delay'] = 1000;
            break;
        case 'success':
            option['addclass'] = 'bg-success';
            option['delay'] = 500;
            break;
        case 'warning':
            option['addclass'] = 'bg-warning';
            option['delay'] = 1000;
            break;
        case 'info':
            option['addclass'] = 'bg-info';
            option['delay'] = 500;
            break;
    }


    new PNotify(option);
}

var GetRequest = function () {
    var url = location.search; //获取url中"?"符后的字串
    var theRequest = new Object();
    if (url.indexOf("?") != -1) {
        var str = url.substr(1);
        strs = str.split("&");
        for(var i = 0; i < strs.length; i ++) {
            theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
        }
    }
    return theRequest;
}

var urlEncode = function (param, key, encode) {
    if(param==null) return '';
    var paramStr = '';
    var t = typeof (param);
    if (t == 'string' || t == 'number' || t == 'boolean') {
        paramStr += '&' + key + '=' + ((encode==null||encode) ? encodeURIComponent(param) : param);
    } else {
        for (var i in param) {
            var k = key == null ? i : key + (param instanceof Array ? '[' + i + ']' : '.' + i);
            paramStr += urlEncode(param[i], k, encode);
        }
    }
    return paramStr;
};

$("#search-button").on('click', function(){
    // console.log('start')
    var url = {};
    var $search_input = $(".search-input");
    // console.log($search_input)
    $.each($search_input, function (i, e){
        url[$(e).data('name')] = $(e).val()
    })
    location.href = $(this).data('href') + '?' + urlEncode(url);
    // console.log()
    // console.log('end')

})

$(".logout").on('click', function(){
    var option = {
        title: '退出',
        text: '<p>是否退出控制台?</p>',
        hide: false,
        type: 'info',
        confirm: {
            confirm: true,
            buttons: [
                {
                    text: '确认',
                    addClass: 'btn btn-sm btn-primary'
                },
                {
                    text: '取消',
                    addClass: 'btn btn-sm btn-default'
                }
            ]
        },
        buttons: {
            closer: false,
            sticker: false
        },
        history: {
            history: false
        }
    }

    option['addclass'] = 'bg-info';

    var notice = new PNotify(option);

    notice.get().on('pnotify.confirm', function(e, notice, val) {
        $.post("/" + admpath + "/logout", {}, function(res){
            if(res.errcode == 0){
                otoast('success', res);
                setTimeout(function(){
                    location.href = "/" + admpath;
                }, 500);
            }
        })
    })
})

$("#reload").on('click', function(){
    location.reload();
})

// $(".inputhelp").hide();

// $(".inputShowhelp").on('focus', function(){
//     var id = $(this).data('id');
//     $("#help_" + id).show();
// })

// $(".inputShowhelp").on('blur', function(){
//     var id = $(this).data('id');
//     $("#help_" + id).hide();
// })

$(".submit-form").on('click', function(){
    var btn = $(this);
    btn.button('loading')
    var $form = $("#" + $(this).data('form'));
    var $href = $form.attr('action');
    var $done = $form.attr('done');
    var data = $form.serialize();
    if(!$href)$href = "";

    var options = {
        text: "请稍后...",
        addclass: 'bg-primary',
        type: 'info',
        icon: 'icon-spinner4 spinner',
        hide: false,
        buttons: {
            closer: false,
            sticker: false
        },
        opacity: .9,
        width: "170px"
    };
    var notice = new PNotify(options);


    $.post($href, data, function(res){
        btn.button('reset')
        if(res.errcode == 0){
            options.title = "完成";
            options.addclass = "bg-success";
            options.type = "success";
            options.icon = 'icon-checkmark3';
            options.delay = 1500;
            if($done){
                setTimeout(function(){
                    location.href = $done;
                }, 1000)
            }
        } else {
            options.title = "错误";
            options.addclass = "bg-danger";
            options.type = "danger";
            options.icon = 'icon-cross2';
        }
        options.text = res.message;
        options.hide = true;
        options.buttons = {
            closer: true,
        };
        options.opacity = 1;
        options.width = PNotify.prototype.options.width;

        notice.update(options);
    })
})

$('ajax').on('click', function(e){
    var href = $(this).attr('href');
    var type = $(this).attr('type');
    var done = $(this).attr('done');

    var prompt = $(this).attr('prompt');
    var prompt_title = $(this).attr('prompt_title');
    var prompt_text = $(this).attr('prompt_text');
    var prompt_color = $(this).attr('prompt_color');
    var prompt_confirm_btn_text = $(this).attr('prompt_btn_text');
    // console.log(prompt);
    // console.log(prompt_title);
    // console.log(prompt_text);
    // console.log(prompt_color);
    // console.log(prompt_confirm_btn_text);
    // console.log(href);
    // console.log(type);
    // console.log(done);
    if(!href)href = ''
    if(!type)type = 'get'


    if(prompt){
        swal({
            title: prompt_title,
            text: prompt_text,
            type: prompt,
            showCancelButton: true,
            confirmButtonColor: prompt_color,
            confirmButtonText: prompt_confirm_btn_text,
            cancelButtonText: "取消",
        },
            function(){
                ajax_fun(href, type, done)
            });
    } else {
        ajax_fun(href, type, done)
    }




})

var ajax_fun = function(href, type, done){
    var options = {
        text: "请稍后...",
        addclass: 'bg-primary-300',
        type: 'info',
        icon: 'icon-spinner4 spinner',
        hide: false,
        buttons: {
            closer: true,
            sticker: false
        },
        opacity: 1,
        width: "170px"
    };
    var notice = new PNotify(options);
    $.ajax({
        url: href,
        type: type,
        data: {},
        success: function(res){
            if(res.errcode == 0){
                options.title = "完成";
                options.addclass = "bg-success";
                options.type = "success";
                options.icon = 'icon-checkmark3';
                options.delay = 1000;
                if(done){
                    setTimeout(function(){
                        location.href = done;
                    }, 1000)
                }
            } else {
                options.title = "错误";
                options.addclass = "bg-danger";
                options.type = "danger";
                options.icon = 'icon-cancel-circle2';
                options.delay = 1000;
            }
            options.hide = true;
            options.text = res.message;
            options.width = PNotify.prototype.options.width;
            notice.update(options);
        },
        statusCode: {
            500: function(){
                options.title = "错误";
                options.addclass = "bg-danger";
                options.type = "danger";
                options.icon = 'icon-cancel-circle2';
                options.delay = 1000;
                options.text = '请求无响应';
                options.width = PNotify.prototype.options.width;
                notice.update(options);
            },
            502: function(){
                options.title = "错误";
                options.addclass = "bg-danger";
                options.type = "danger";
                options.icon = 'icon-cancel-circle2';
                options.delay = 1000;
                options.text = '请求无响应';
                options.width = PNotify.prototype.options.width;
                notice.update(options);
            },
            404: function() {
                options.title = "错误";
                options.addclass = "bg-warning";
                options.type = "danger";
                options.icon = 'icon-warning22';
                options.delay = 1000;
                options.text = '请求的页面不存在';
                options.width = PNotify.prototype.options.width;
                notice.update(options);
            }
        }
    });
}


// Checkboxes, radios
$(".styled").uniform({ radioClass: 'choice' });

// File input
$(".file-styled").uniform({
    fileButtonClass: 'action btn bg-pink-400'
});
