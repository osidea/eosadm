<?php

namespace EOSFM\Framework\Controllers;

use App\Http\Controllers\Controller;
use EOSFM\Framework\Extended\UCenter;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;


class System extends Common
{

    public function __construct()
    {
        parent::__construct();
    }

    public function auth()
    {
        switch(I('get.o')){
            case 'save':
                if(IS_POST){
                    if(DS('auth_rule', $this->input, I('get.id'))){
                        return $this->apiReturn(0, '操作成功');
                    } else {
                        return $this->apiReturn(-2, '操作失败');
                    }
                } else {
                    $info = D('auth_rule') -> where(['id' => I('id')]) -> first();
                    $page_title = $info?'编辑':'新增';
                    return V('system.auth.save', $page_title.'规则', compact('info'));
                }
                break;
            default:
                $list = D('auth_rule') -> get();
                return V('system.auth.index', '权限规则', compact('list'));
                break;
        }
    }

    public function config()
    {
        
        switch(I('get.o')){
            case 'group':
                if(IS_POST){
                    unset($this->input['id']);
                    unset($this->input['o']);
                    if(DS('system_config_group', $this->input, I('get.id'))){
                        return $this->apiReturn(0, '操作成功');
                    } else {
                        return $this->apiReturn(-2, '操作失败');
                    }
                } else {
                    return V('system.config.group', '配置分组');
                }
                break;
            case 'config':
                if(IS_POST){
                    unset($this->input['id']);
                    unset($this->input['o']);
                    if(DS('system_config', $this->input, I('get.id'))){
                        return $this->apiReturn(0, '操作成功');
                    } else {
                        return $this->apiReturn(-2, '操作失败');
                    }
                } else {
                    $info = D('system_config') -> where(['id' => I('id')]) -> first();
                    $page_title = $info?'编辑':'新增';
                    return V('system.config.config', $page_title. '配置', compact('info'));
                }
                break;
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
                    $model_name = $this->input['model_name'];
                    $table = '$table';
                    $dateFormat = '$dateFormat';
                    if(DS('system_model', $this->input, I('get.id'))){
                        $model = <<<model
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class $model_name extends Model
{
    //
    protected $table = '$model_name';
    protected $dateFormat = 'U';
}

model;

                        file_put_contents(app_path('Models/'. $model_name. '.php'), $model);

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
            case 'del':
                $re = D('system_model') -> where(['id' => I('id')]) -> delete();
                if($re){
                    return $this->apiReturn(0, '模型已成功删除');
                } else {
                    return $this->apiReturn(-2, '删除失败,请刷新此页面后重试。');
                }
                break;
            case 'generate':
                $fields = D('system_model_field') -> where(['model_name' => I('model_name')]) -> get();
                if(count($fields)){
                    if (Schema::hasTable(I('model_name'))) {
                        if(I('re') == 1){
                            Schema::drop(I('model_name'));
                        } else {
                            return $this->apiReturn(-2, '模型已生成,如需要更新或重新生成,请先删除此表');
                        }
                        
                    }

                    try{
                        Schema::create(I('model_name'), function (Blueprint $table) use($fields) {
                            $table->increments('id');
                            foreach($fields as $value){
                                $name = $value->field;
                                switch($value->type){
                                    case 'text':
                                        $table->text($name)->nullable();
                                        break;
                                    case 'number':
                                        $table->integer($name)->default(0);
                                        break;
                                    default:
                                        $table->string($name)->nullable();
                                        break;
                                }
                            }

                            $table->string('created_at', 100)->nullable();
                            $table->string('updated_at', 100)->nullable();
                        });
                        DS('system_model', ['status' => 1], ['model_name' => I('model_name')]);
                        return $this->apiReturn(0, '模型已生成');
                    } catch (\Exception $e) {
                        Schema::drop(I('model_name'));
                        return $this->apiReturn(-2, $e->getMessage());
                    }
                } else {
                    return $this->apiReturn(-2, '没有可生成的字段');
                }
                break;
            default:
                if(!is_dir(app_path('Models'))){
                    mkdir (app_path('Models'),0777,true);
                }
                $list = D('system_model') -> get();
                return V('system.model.index', '模型管理', compact('list'));
                break;
        }
    }

    public function field()
    {
        switch(I('get.o')){
            case 'save':
                if(IS_POST){
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
            case 'del':
                $re = D('system_model_field') -> where(['id' => I('id')]) -> delete();
                if($re){
                    return $this->apiReturn(0, '字段已成功删除');
                } else {
                    return $this->apiReturn(-2, '删除失败,请刷新此页面后重试。');
                }
                break;
            default:
                $list = D('system_model_field') -> where(['model_name' => I('model_name')]) -> get();
                foreach($list as $key => $value){
                    switch($value -> type){
                        case 'text':
                            $list[$key]['type_text'] = '文本';
                            break;
                        case 'number':
                            $list[$key]['type_text'] = '数字';
                            break;
                        case 'password':
                            $list[$key]['type_text'] = '密码';
                            break;
                        case 'select':
                            $list[$key]['type_text'] = '下拉选项';
                            break;
                        case 'radio':
                            $list[$key]['type_text'] = '单选';
                            break;
                        default:
                            $list[$key]['type_text'] = '输入框';
                            break;
                    }
                }
                $model = D('system_model') -> get();
                return V('system.field.index', '字段列表', compact('list', 'model'));
                break;
        }
    }



}