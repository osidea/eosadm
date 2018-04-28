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

    public function model()
    {
        switch(I('get.o')){
            case 'save':
                if(IS_POST){
                    unset($this->input['id']);
                    unset($this->input['o']);
                    if(DS('system_model', $this->input, I('get.id'))){
                        return $this->apiReturn(0, '操作成功');
                    } else {
                        return $this->apiReturn(-2, '操作失败');
                    }
                } else {
                    $info = D('system_model') -> where(['id' => I('id')]) -> first();
                    $page_title = $info?'编辑':'新增';
                    return V('system.model.save', $page_title.'模型', compact('info'));
                }
                break;
            default:
                if(IS_POST){

                } else {
                    return V('system.model.index', '模型管理');
                }
                break;
        }
    }

    public function field()
    {
        switch(I('get.o')){
            case 'save':
                if(IS_POST){
                    unset($this->input['id']);
                    unset($this->input['o']);
                    if(DS('system_model_field', $this->input, I('get.id'))){
                        return $this->apiReturn(0, '操作成功');
                    } else {
                        return $this->apiReturn(-2, '操作失败');
                    }
                } else {
                    $info = D('system_model_field') -> where(['id' => I('id')]) -> first();
                    $page_title = $info?'编辑':'新增';
                    return V('system.field.save', $page_title.'字段', compact('info'));
                }
                break;
            default:
                if(IS_POST){

                } else {
                    return V('system.field.index', '模型管理');
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