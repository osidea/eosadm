/* ------------------------------------------------------------------------------
*
*  # Login page
*
*  Specific JS code additions for login and registration pages
*
*  Version: 1.0
*  Latest update: Aug 1, 2015
*
* ---------------------------------------------------------------------------- */

$(function() {
    $("#Ea_sb31").on('click', function(){
        var btn = $(this);
        btn.button('loading')
        var $dom = $(".login-container");
        $($dom).block({
            message: '<i class="icon-spinner10 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#1B2024',
                opacity: 0.85,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none',
                color: '#fff'
            }
        });

        $.post("", {Ex_un32: $("#Ex_un32").val(), Ep_pw33: $("#Ep_pw33").val()}, function(res){
            $($dom).unblock();
            if(res.errcode == 0){
                var getUrl = GetRequest();
                storage.setItem('user_token', res.result.token)
                storage.setItem('log', res.result.log)
                // console.log(getUrl.backurl);return false;
                if(getUrl.backurl){
                    setTimeout(function(){
                        location.href = getUrl.backurl;
                    }, 1000);
                } else {
                    setTimeout(function(){
                        location.href = res.result.url
                    }, 1000);
                }

                otoast('success', {message:'登陆成功'});
            } else {
                btn.button('reset')
                otoast('error', res);
            }
        })
    })
});
