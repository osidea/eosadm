<?php

namespace EOSFM\Framework\Controllers;

use App\Http\Controllers\Controller;
use EOSFM\Framework\Extended\UCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;


class Common extends Controller
{
    public $input;

    public function __construct()
    {
        $this->input = Input::all();
    }

    public function apiReturn($errcode=-1, $message='', $data=[])
    {
        return apiReturn($errcode, $message, $data);
    }

    public function login(Request $request)
    {
        if(session('admin.token') && session('admin.is_adm') == 1){
            return R('');
        }
        if(IS_POST){
            if(!$this->input['Ex_un32']){
                $this->apiReturn(1, '用户名不能为空');
            }
            if(!$this->input['Ep_pw33']){
                $this->apiReturn(1, '密码不能为空');
            }

            return D('ucenter_member') -> check([
                'username' => $this->input['Ex_un32'],
                'password' => $this->input['Ep_pw33'],
            ], 'admin');

        } else {
            return V('login', '系统登陆');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin');
        return $this->apiReturn(0, '退出成功');
    }

}