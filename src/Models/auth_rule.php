<?php

namespace EOSFM\Framework\Models;

use Illuminate\Database\Eloquent\Model;

class auth_rule extends Model
{
    //
    protected $table = 'auth_rule';
    protected $dateFormat = 'U';

    public $pid = [];

    public function select($whereauth)
    {
        $list = $this->get();
        foreach($list as $key => $value){
            $list[$key]['url'] = admurl($value['c'].'/'.$value['f'], ['o' => $value['o']?$value['o']:null, 'to' => $value['to']?$value['to']:null]);
            if($whereauth['c'] == $value['c'] && $whereauth['f'] == $value['f']){
                $list[$key]['active'] = 'active';
                if($value['pid'] > 0){
                    $this -> pid[] = $value['pid'];
                }
            } else {
                $list[$key]['active'] = '';
            }
        }

        $this->get_pid();

        foreach($list as $key => $value){
            if(in_array($value['id'], $this -> pid)){
                $list[$key]['active'] = 'active';
            }
        }
        $list = list2tree($list);
        return $list;
    }

    public function get_pid()
    {
        $num = 0;
        if($this->pid){
            $re = $this->whereIn('id', $this->pid) -> get();
            if($re){
                foreach($re as $value){
                    if($value['pid'] > 0 && !in_array($value['pid'], $this->pid)){
                        $this -> pid[] = $value['pid'];
                        $num++;
                    }
                }
            }
        }
        if($num > 0){
            $this->get_pid();
        }
    }
}
