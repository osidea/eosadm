<?php

namespace EOSFM\Framework\Models;

use Illuminate\Database\Eloquent\Model;

class ucenter_member extends Model
{
    //
    protected $table = 'ucenter_member';
    protected $dateFormat = 'U';

    public static function apiReturn($errcode=-1, $message='', $data=[])
    {
        $return = [
            'errcode' => $errcode,
            'message' => $message,
            'result' => $data,
        ];
        return ($return);
    }

    public function create($userinfo){
        $db = D('ucenter_member');
        foreach($userinfo as $key => $value){
            if($key == 'password'){
                $value = $this->password_encode($userinfo['username'], $userinfo['password']);
            }
            $db -> $key = $value;
        }
        try{
            if($db -> save()){
                $return = [
                    'errcode' => 0,
                    'message' => '创建成功',
                ];
            }
            return $return;
        } catch (\Exception $e) {
            switch($e->getCode()){
                case '23000':
                    if(stripos($e->getMessage(), 'os_users_username_unique') !== false){
                        $return = [
                            'errcode' => 23000.1,
                            'message' => '用户名已存在',
                        ];
                    } else if(stripos($e->getMessage(), 'os_users_phone_unique') !== false){
                        $return = [
                            'errcode' => 23000.2,
                            'message' => '手机号已被其他账号绑定',
                        ];
                    }
                    break;
                default:
                    $return = [
                        'errcode' => 500,
                        'message' => $e->getMessage(),
                    ];
                    break;
            }
            return $return;
        }
    }

    public function check($logininfo, $mode=null)
    {
        $where = [
            'username' => @$logininfo['username'],
            'phone' => @$logininfo['username'],
        ];

        $db = D('ucenter_member')::orWhere($where) -> where(['password' => $this->password_encode(@$logininfo['username'], @$logininfo['password'])]) -> first();
        if($db){
            if($mode=='admin'){
                if(str_split($db -> auth)[0] == 'y' && $db -> status == 1){
                    $token = sha1(base64_encode($db->id). time());
                    $session = [
                        'token' => $token,
                        'auth' => str_split($db -> auth),
                        'is_adm' => 1,
                    ];
                    self::setlogin($session, $mode);
                    S('token_'. $token, $db->id);
                    systemlog('admlogin', '管理员[' . $db -> username . ']登陆系统');
                    return self::apiReturn(0, '登陆成功', ['token' => $token, 'log' => time(), 'url' => '/'. config('admin.perfix')]);
                } else {
                    systemlog('admlogin', '尝试使用[' . $logininfo['username'] . ':'.$logininfo['password'].']登陆系统');
                    return self::apiReturn(1, '用户名或密码不正确');
                }
            }
        } else {
            systemlog('admlogin', '尝试使用[' . $logininfo['username'] . ':'.$logininfo['password'].']登陆系统');
            return self::apiReturn(1, '用户名或密码不正确');
        }

    }

    public static function setlogin($save, $mode)
    {
        $session[$mode] = $save;
        session($session);
    }

    public function password_encode($username, $password, $key=''){
        if($key == 'env'){
            $key = str_replace('base64:', '', env('APP_KEY'));
        }
        return sha1(md5(''). ':'. md5($password). ':'. $key);
    }
}
