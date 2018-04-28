<?php

namespace EOSFM\Framework\Controllers;

use App\Http\Controllers\Controller;
use EOSFM\Framework\Extended\UCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class Index extends Common
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if(IS_POST){
            $re = DS('system_model', $this->input);
            if($re){
                return $this->apiReturn(0, '新增成功');
            }
        } else {
//            $info = D('system_model') -> where(['id' => $this->input]) -> first();
            return V('index.index', '管理控制台');
        }
    }

    public function test()
    {
        return V('index.index', '测试页面');

    }
}