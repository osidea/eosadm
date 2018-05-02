<?php

namespace EOSFM\Framework\Middleware;

use Closure;
use EOSFM\Framework\Extended\UCenter;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class EOSCheck
{

    protected $except = [
        '/login',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        loadconfig();
		
		$nowurl = (str_replace('/' . config('admin.perfix'), '', @$_SERVER['REDIRECT_URL']?$_SERVER['REDIRECT_URL']:$_SERVER['REQUEST_URI']));
		$nowurl = explode('?', $nowurl)[0];

        if (!session('admin.token') || session('admin.is_adm') != 1) {
            if (!in_array(str_replace('/' . config('admin.perfix'), '', $nowurl), $this->except)) {
                return R('login', ['backurl' => $_SERVER['REQUEST_URI']]);
            }
        }

        if(session('admin.token')){
            $uid = S('token_'. session('admin.token'));
            $request -> uid = $uid;
            View::share('userinfo', UCenter::find($uid));
        }

        $config_group = M('system_config_group') -> get();
        View::share('setlist', $config_group);

        $pathInfo = str_replace(['/' . config('admin.perfix'). '/', '/' . config('admin.perfix')], '', $request -> getPathInfo());
        $pathInfo = explode('/', $pathInfo);
        if(!@$pathInfo[0]){
            $pathInfo[0] = 'index';
        }
        if(!@$pathInfo[1]){
            $pathInfo[1] = 'index';
        }
        $authwhere = [
            'c' => $pathInfo[0],
            'f' => $pathInfo[1],
        ];
        if(I('get.o')){
            $authwhere['o'] = I('get.o');
        }
        if(I('get.to')){
            $authwhere['to'] = I('get.to');
        }
        $nowpage = M('auth_rule') -> where($authwhere) -> first();


        $nav = D('auth_rule') -> select($authwhere);
        View::share('nav', $nav);



//        $authlist = [
//            ['id' => 1, 'pid' => 0, 'name' => '管理控制台', 'c' => '', 'a' => '', 'o' => '', 'type' => 1],
//            ['id' => 9000, 'pid' => 0, 'name' => '用户管理', 'c' => 'ucenter', 'a' => 'member', 'type' => 1],
//            ['id' => 10000, 'pid' => 0, 'name' => '配置管理', 'c' => 'system', 'a' => 'config', 'type' => 1],
//            ['id' => 10001, 'pid' => 10000, 'name' => '新增配置', 'c' => 'system', 'a' => 'config' , 'o' => 'add', 'action' => 1],
//            ['id' => 10002, 'pid' => 10000, 'name' => '编辑配置', 'c' => 'system', 'a' => 'config' , 'o' => 'edit', 'action' => 1],
//            ['id' => 10003, 'pid' => 10000, 'name' => '删除配置', 'c' => 'system', 'a' => 'config' , 'o' => 'delete', 'action' => 1],
//        ];
//        foreach($authlist as $key => $value){
//            if(@$value['c'] == @$pathInfo[0] && @$value['a'] == @$pathInfo[1] && @$value['o'] == I('get.o')){
//                $page_title = $value['name'];
//            }
//        }
//        View::share('page_title', @$page_title?$page_title:'未命名');

        return $next($request);
    }

}
