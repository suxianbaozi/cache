<?php

class Bll {
    
    public function assoc_data($arr,$key) {
        if(!is_array($arr)){
            return array();
        }
        $result = array();
        foreach($arr as $v) {
            $result[$v[$key]] = $v;
        }
        return $result;
    }   
    
    public function merge_data($arr1,$arr2,$assoc_key) {
        foreach($arr1 as $k1=>$v1) {
            foreach($arr2[$v1[$assoc_key]] as $k2=>$v2) {
                $arr1[$k1][$k2] = $v2;
            }
        }
        return $arr1;
    }
    public function get_ids($array,$key) {
        $result = array();
        foreach($array as $v) {
            $result[] = $v[$key];
        }
        return $result;
    }
    
}
