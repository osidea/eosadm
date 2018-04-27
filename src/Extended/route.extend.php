<?php



if(!function_exists('getRoute')){
    function getRoute($r=null){
        $route = [
            'dashboard' => ['name' => '管理控制台', 'c' => 'index', 'f' => 'index'],
            'system.config' => 'system/config',
            'systen.config.add' => 'system/config/add',
        ];

        if(!$r){
            return $route;
        } else {
            return $route[$r];
        }
    }
}