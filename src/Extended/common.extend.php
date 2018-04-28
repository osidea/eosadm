<?php

if(!function_exists('admurl')){
    function admurl($url='', $var=null){
        return U('/'. config('admin.perfix'). '/'. $url, $var);
    }
}

if(!function_exists('apiReturn')){
    function error_msg(){
        $err = [
            -2 => 'ERROR', //记录日记
            -1 => 'ERROR', //错误
            0 => 'SUCCESS', //成功
            1 => 'FAIL_UNAUTHORIZED_ACCESS', //未授权访问
            2 => 'FAIL_INVALID_APPID', //无效的appid
        ];
        return $err;
    }

    function apiReturn($errcode=-1, $message='', $data=[]){
//        header('Content-type: application/json');
        $message = $message?$message:(@error_msg()[$errcode]?@error_msg()[$errcode]:'unknown error');
        $return = [
            'errcode' => $errcode,
            'message' => $message,
            'result' => $data,
        ];
        return $return;
    }
}

if(!function_exists('base_form')){
    function base_form($form_name){
        $domHtml = '';
        $model = D('system_model') -> where(['model_name' => $form_name]) -> first();
        if($model){
            $action = $model -> action;
            $done = $model -> done;
            $dom = D('system_model_field') -> where(['model_name' => $form_name]) -> get();
            foreach($dom as $key => $value){
                $name = $value['name'];
                $field = $value['field'];
                $placeholder = @$value['placeholder'];
                $values = @$value['default_value'];
                $remark = @$value['remark'];
                switch(@$value['type']){
                    case 'text':
                        $tmpDom = <<<html
<textarea class="form-control inputShowhelp" data-id="$key" name="$name" rows="5" cols="5">$values</textarea>
html;
                        break;
                    case 'select':
                        $option = '';

                        $tmp = explode(',', $value -> extra_custom);
                        foreach($tmp as $_value){
                            $option_tmp = explode(':', $_value);
                            if(@$option_tmp[0] == $values){
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                            $option .= '<option value="'.@$option_tmp[0].'" '.$selected.'>'.@$option_tmp[1].'</option>';
                        }

                        if($value -> extra_model){
                            $where = json_decode($value -> extra_where);
                            if(!$where) $where = [];
                            $list = D($value -> extra_model) -> where($where) -> get();
                            foreach($list as $_key => $_value){
                                $extra_value = $value['extra_value'];
                                $extra_name = $value['extra_name'];
                                if(@$_value -> $extra_value == $values){
                                    $selected = 'selected="selected"';
                                } else {
                                    $selected = '';
                                }
                                $option .= '<option value="'.@$_value -> $extra_value.'" '.@$selected.'>'.@$_value -> $extra_name.'</option>';
                            }

                        }

                        $tmpDom = <<<html
<select class="form-control inputShowhelp" name="$name" data-id="$key">
$option
</select>
html;
                        break;
                    default:
                        $tmpDom = <<<html
<input type="text" class="form-control inputShowhelp" data-id="$key" name="$field" placeholder="$placeholder" value="$values">
html;
                        break;
                }


                $domHtml .= <<<html
<tr>
    <td>$name</td>
    <td>
        $tmpDom
    </td>
    <td><span id="help_$key" class="help-inline inputhelp" hidden>$remark</span></td>
</tr>
html;

            }

            $html = <<<html
 <form id="$form_name" action="$action" done="$done">
    <table class="table table-striped">
        <tbody>
        $domHtml
        <tr>
            <td><i class="text-danger">*</i> 为必填</td>
            <td>
                <button type="button" class="btn btn-info submit-form" data-loading-text="<i class='icon-spinner10 spinner position-left'></i> 提交中..." data-form="$form_name">提交</button>
            </td>
            <td></td>
        </tr>
        </tbody>
    </table>
</form>
html;
        } else {
            $html = '没有找到['.$form_name.']模型';
        }


        return $html;
    }
}

if(!function_exists('loadconfig')){
    /**
     * 读取系统配置
     */
    function loadconfig(){
        $config = config_lists();
        C($config);
        return true;
    }
}

if(!function_exists('config_lists')){
    /**
     * 获取数据库中的配置列表
     * @return array 配置数组
     */
    function config_lists(){
//    $map = array('status' => 1);
        $data = \Illuminate\Support\Facades\Cache::rememberForever('config', function() {
            return D('system_config') -> get();
        });

//    $data = M('system_config') -> get();
        $config = array();
        if($data){
            foreach ($data as $value) {
//            $config[$value->name] = parse($value->type, $value->value);
                $config[$value -> name] = $value -> value;
            }
        }
        return $config;
    }
}


if(!function_exists('list2tree')){
    function list2tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0)
    {
        // 创建Tree
        $tree = array();
        if(is_object($list)) {
            $list = object2array($list);
        }
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId =  $data[$pid];
                if ($root === $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}

if(!function_exists('object2array')){
    function object2array(&$object) {
        $object = json_decode(json_encode($object),true);
        return $object;
    }
}

if(!function_exists('systemlog')){
    function systemlog($mode, $remark, $uid=0){
        $data = [
            'remote_addr' => $_SERVER['REMOTE_ADDR'],
            'request_method' => $_SERVER['REQUEST_METHOD'],
            'http_user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'created_at' => time(),
            'updated_at' => time(),
        ];
        switch($mode){
            case 'admlogin':
                $data['remark'] = $remark;
                DS('log_admlogin', $data);
                break;
            case 'admaction':
                $data['uid'] = $uid;
                $data['remark'] = $remark;
                DS('log_admaction', $data);
                break;
        }


    }

}

if(!function_exists('AV')){
    function AV($class='', $action='', $template_name=''){
        if($class){
            $class = '/'. $class;
        }

        if($action){
            $action = '/'. $action;
        }
        $dir = $class. $action;

        if($dir){
            $dir = $dir. '/';
        }
        $template_name = I('o');

        if(!$template_name){
            $template_name = 'index';
        }

        return view('eosadm::'. $dir.$template_name);
    }
}


if(!function_exists('C')){
    function C($name=null, $value=null,$default=null) {
        static $_config = array();
        // 无参数时获取所有
        if (empty($name)) {
            return $_config;
        }
        // 优先执行设置获取或赋值
        if (is_string($name)) {
            if (!strpos($name, '.')) {
                $name = strtoupper($name);
                if (is_null($value))
                    return isset($_config[$name]) ? $_config[$name] : $default;
                $_config[$name] = $value;
                return null;
            }
            // 二维数组设置和获取支持
            $name = explode('.', $name);
            $name[0]   =  strtoupper($name[0]);
            if (is_null($value))
                return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : $default;
            $_config[$name[0]][$name[1]] = $value;
            return null;
        }
        // 批量设置
        if (is_array($name)){
            $_config = array_merge($_config, array_change_key_case($name,CASE_UPPER));
            return null;
        }
        return null; // 避免非法参数
    }
}

if(!function_exists('CU')){
    function CU($host = "", $method = "get", $querys = 0, $appcode = ""){
        $headers = array();
        if($appcode){
            array_push($headers, "Authorization:APPCODE " . $appcode);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $host);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $querys);
        }elseif($method == 'get'){
            curl_setopt($ch, CURLOPT_HEADER, 0);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}

if(!function_exists('D')){
    function D($model_name){
        $model = '\\EOSFM\Framework\Models\\'.$model_name;
        if(class_exists($model)){
            return new $model;
        } else {
            abort(403, '找不到模型：'. $model_name);
        }
    }
}


if(!function_exists('DS')){
    function DS($model_name, $data, $id=null){
        if($id){
            if(is_array($id)){
                $db = D($model_name)::where($id);
                if($db -> update($data)){
                    return true;
                } else {
                    return false;
                }
            } else if(is_numeric($id)) {
                $db = D($model_name)::find($id);
            }
        } else {
            $db = D($model_name);
        }

        foreach($data as $key => $value){
            $db -> $key = $value;
        }
        if($db -> save()){
            return true;
        } else {
            return false;
        }
    }
}

if(!function_exists('I')){
    function I($name,$default='',$filter=null,$datas=null) {
        if(strpos($name,'.')) { // 指定参数来源
            list($method,$name) =   explode('.',$name,2);
        }else{ // 默认为自动判断
            $method =   'param';
        }
        switch(strtolower($method)) {
            case 'get'     :   $input =& $_GET;break;
            case 'post'    :   $input =& $_POST;break;
            case 'put'     :   parse_str(file_get_contents('php://input'), $input);break;
            case 'param'   :
                switch($_SERVER['REQUEST_METHOD']) {
                    case 'POST':
                        $input  =  $_POST;
                        break;
                    case 'PUT':
                        parse_str(file_get_contents('php://input'), $input);
                        break;
                    default:
                        $input  =  $_GET;
                }
                break;
//        case 'path'    :
//            $input  =   array();
//            if(!empty($_SERVER['PATH_INFO'])){
//                $depr   =   C('URL_PATHINFO_DEPR');
//                $input  =   explode($depr,trim($_SERVER['PATH_INFO'],$depr));
//            }
//            break;
            case 'request' :   $input =& $_REQUEST;   break;
            case 'session' :   $input =& $_SESSION;   break;
            case 'cookie'  :   $input =& $_COOKIE;    break;
            case 'server'  :   $input =& $_SERVER;    break;
            case 'globals' :   $input =& $GLOBALS;    break;
            case 'data'    :   $input =& $datas;      break;
            default:
                return NULL;
        }
        if(''==$name) { // 获取全部变量
            $data       =   $input;
            array_walk_recursive($data,'filter_exp');
            $filters    =   isset($filter)?$filter:C('DEFAULT_FILTER');
            if($filters) {
                if(is_string($filters)){
                    $filters    =   explode(',',$filters);
                }
                foreach($filters as $filter){
                    $data   =   array_map_recursive($filter,$data); // 参数过滤
                }
            }
        }elseif(isset($input[$name])) { // 取值操作
            $data       =   $input[$name];
            is_array($data) && array_walk_recursive($data,'filter_exp');
            $filters    =   isset($filter)?$filter:C('DEFAULT_FILTER');
            if($filters) {
                if(is_string($filters)){
                    $filters    =   explode(',',$filters);
                }elseif(is_int($filters)){
                    $filters    =   array($filters);
                }

                foreach($filters as $filter){
                    if(function_exists($filter)) {
                        $data   =   is_array($data)?array_map_recursive($filter,$data):$filter($data); // 参数过滤
                    }else{
                        $data   =   filter_var($data,is_int($filter)?$filter:filter_id($filter));
                        if(false === $data) {
                            return   isset($default)?$default:NULL;
                        }
                    }
                }
            }
        }else{ // 变量默认值
            $data       =    isset($default)?$default:NULL;
        }
        return $data;
    }
}

if(!function_exists('M')){
    function M($name=null)
    {
        if(is_null($name) || !$name){
            return null;
        } else {
            return \Illuminate\Support\Facades\DB::table($name);
        }
    }
}

if(!function_exists('R')){
    function R($url, $data = null){
        if(!is_array($data)){
            $data = null;
        }

//        if(@!$data['backurl']){
//            $data['backurl'] = $_SERVER['REQUEST_SCHEME']. '://'. $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
//        }
        return redirect('/'.config('admin.perfix').'/'.$url. ($data?'?'. http_build_query($data): ''));
    }
}

if(!function_exists('S')){
    function S($name, $data=null, $time=0){
        if($time == 0){
            $return = \Illuminate\Support\Facades\Cache::rememberForever($name, function() use($data) {
                return $data?$data:null;
            });
        } else {
            $return = \Illuminate\Support\Facades\Cache::remember($name, $time, function() use($data) {
                return $data?$data:null;
            });
        }

        return $return;
    }
}

if(!function_exists('U')){
    /**
     * URL组装
     * @param string $url URL表达式
     * @param string|array $vars 传入的参数，支持数组和字符串
     * @return string
     */
    function U($url=null, $vars=null, $domain=true){
        if(is_null($url)){
            $url = $_SERVER['SCRIPT_URI'];
        }
        // 解析参数
        if(is_string($vars)) { // aaa=1&bbb=2 转换成数组
            parse_str($vars,$vars);
        }elseif(!is_array($vars)){
            $vars = array();
        }
        if(isset($info['query'])) { // 解析地址里面参数 合并到vars
            parse_str($info['query'],$params);
            $vars = array_merge($params,$vars);
        }
        if($domain){
            $url = url($url);
        }
        if($vars){
            $vars   =   http_build_query($vars);
            if($vars){
                $url   .=   '?&'.$vars;
            }
        }
        return $url;
    }
}

if(!function_exists('V')){
    /**
     * @param string $template_name 模板名称
     * @param string $page_title 页面标题
     * @param array $data 传递数据
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function V($template_name='', $page_title='', $data=[]){
        if(!$template_name){
            abort(403, '找不到模板页面：'. $template_name);
            return false;
        }
        $data['page_title'] = $page_title;
        return view('eosadm::'. $template_name, $data);
    }
}
