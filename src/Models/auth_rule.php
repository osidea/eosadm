<?php

namespace EOSFM\Framework\Models;

use Illuminate\Database\Eloquent\Model;

class auth_rule extends Model
{
    //
    protected $table = 'auth_rule';
    protected $dateFormat = 'U';



//    protected $pid = [];

    public function select($whereauth)
    {
        $pid = [];

        $list = $this->where(['show' => 1, 'status' => 1])->get();
        foreach($list as $key => $value){
            $list[$key]['url'] = admurl($value['c'].'/'.$value['f'], ['o' => $value['o']?$value['o']:null, 'to' => $value['to']?$value['to']:null]);
            if($whereauth['c'] == $value['c'] && $whereauth['f'] == $value['f']){
                $list[$key]['active'] = 'active';
                if($value['pid'] > 0){
                    $pid[] = $value['pid'];
                }
            } else {
                $list[$key]['active'] = '';
            }
        }
        $pid = $this->get_pid($pid);

        foreach($list as $key => $value){
            if(in_array($value['id'], $pid)){
                $list[$key]['active'] = 'active';
            }
        }
        $list = list2tree($list);
        return $list;
    }

    public function get_pid($arr)
    {
        $num = 0;
        if($arr){
            $re = $this->whereIn('id', $arr) -> get();
            if($re){
                foreach($re as $value){
                    if($value['pid'] > 0 && !in_array($value['pid'], $arr)){
                        $arr[] = $value['pid'];
                        $num++;
                    }
                }
            }
        }
        if($num > 0){
            $this->get_pid($arr);
        } else {
            return $arr;
        }
    }
}
