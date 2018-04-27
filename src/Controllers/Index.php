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
        return V('index.index', '管理控制台');
    }

    public function test()
    {
        return V('index.index', '测试页面');

    }
}