<?php

namespace EOSFM\Framework\Models;

use Illuminate\Database\Eloquent\Model;

class system_config extends Model
{
    //
    protected $table = 'system_config';
    protected $dateFormat = 'U';

    public function select($key=null)
    {
        $where = [];
        if($key){
            $where['group'] = $key;
        }
        $list = $this->where($where)->get();
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
                case 9:
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
        return $list;
    }
}
