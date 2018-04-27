<?php

namespace EOSFM\Framework\Controllers;

use App\Http\Controllers\Controller;
use EOSFM\Framework\Extended\UCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class System extends Common
{

    public function __construct()
    {
        parent::__construct();
    }

    public function config()
    {
        switch(I('get.o')){
            case 'save':

                break;
            default:
                if(IS_POST){
                    foreach($this->input as $key => $value){
                        DS('system_config', ['value' => $value], ['name' => $key]);
                    }
                    return $this->apiReturn(0, '配置已更新');
                } else {
                    $page = I('to', 'site');
                    $mode = I('mode', 'only');
                    $group = M('system_config_group') -> get();
                    $nkey = M('system_config_group') -> where(['key' => $page]) -> first();
                    $list = D('system_config') -> select($page);
                    return V('system.config.index', $nkey->name, compact('page', 'mode', 'nkey', 'group', 'list'));
                }
                break;
        }
    }

    public function run($action='')
    {
        switch($action){
            case 'config':
                switch(I('get.o')){
                    case 'save':

                        break;
                    default:
                        if(IS_POST){
                            foreach($this->input as $key => $value){
                                DS('system_config', ['value' => $value], ['name' => $key]);
                            }
                            return $this->apiReturn(0, '配置已更新');
                        } else {
                            $list = D('system_config') -> get();
                            foreach($list as $key => $value){
                                switch($value -> type){
                                    case 8:
                                        $arr = [];
                                        $tmp = explode(',', $value -> extra);
                                        $list[$key] -> extra = [];
                                        foreach($tmp as $_value){
                                            $option_tmp = explode(':', $_value);
                                            $explode_tmp = [
                                                'name' => $option_tmp[1],
                                                'value' => $option_tmp[0],
                                            ];
                                            $arr[] = $explode_tmp;
                                        }
                                        $list[$key] -> extra = $arr;
                                        break;
                                }
                            }
                            $newlist = [];
                            foreach($list as $key => $value){
                                $newlist[$value['group']][] = $value;
                            }
                            $newlist2 = [];
                            foreach($newlist as $key => $value){
                                $newlist2[] = [
                                    'name' => $key,
                                    'array' => $value,
                                ];
                            }

                            return V('system/config/index', '配置管理', ['list' => $newlist2]);
                        }
                        break;
                }
                break;
            default:
                return R('');
                break;
        }
    }

}