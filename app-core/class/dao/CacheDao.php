<?php
rsf_require_class('Dao');
abstract class Dao_CacheDao extends Dao {
    public $is_cache = true;
    
    public function disable_cache() {
        $this->is_cache = false;
    }
    public function get_pre_key() {
        return $this->get_db_name().'_'.$this->get_table_name().'_';
    }
    public function exeSQL($sql) {
        $this->set_update();
        return parent::exeSQL($sql);
    }
    public function get_count_by_where($where) {
        if(!$this->is_cache) {
            return parent::get_count_by_where($where);
        }
        
        $s = microtime(TRUE);
        $uncached = false;
        //首先按照键值排序
        ksort($where); //缓存更加能命中
        //找到in的情况
        foreach($where as $k=>$v) {
            $wh = explode(" ", $k);
            if($wh[1]=='in') {
                if(is_array($v)) {
                    $v = array_keys(array_flip($v));
                    $where[$k]  = $v;
                }
            }
        }
        $mem = RSF::get_instance()->get_memcache();
        $key = $this->get_pre_key().'count_'.md5(serialize($where));
        
        if($this->is_update($mem->get($key.'_save_time'))) {
            $uncached = true;
        } else {
            $num = $mem->get($key);
            if($num===false) {
                $uncached = true;
            } 
        }
        if($uncached) {
            $num = parent::get_count_by_where($where);
            $mem->set($key,$num,0);
            $mem->set($key.'_save_time',time(),0);
        } else {
            RSF::get_instance()->debug($key.'get_num from cache','from cache');
        }
        return $num;
    }
    public function get_by_id($id) {
        if(!$id) {
            return array();
        }
        if(!$this->is_cache) {
            return parent::get_by_id($id);
        }
        $mem = RSF::get_instance()->get_memcache();
        $key = $this->get_pre_key().$id;
        $result = $mem->get($key);
        if(!$result) {
            $result = parent::get_by_id($id);
            $mem->set($key,$result,0);
        } else {
            RSF::get_instance()->debug($key,'getcache');
        }
        return $result;
    }
    public function build_row_key($id) {
        return $this->get_pre_key().$id;
    }
    
    public function is_update($t) {
        if(!$t) {
            return true;
        }
        $mem = RSF::get_instance()->get_memcache();
        $is_update = $mem->get($this->get_pre_key());
        if(!$is_update) {
            return false;
        }
        if($t>$is_update) {
            return false;
        } else {
            return true;
        }
    }
    public function set_update() {
        $mem = RSF::get_instance()->get_memcache();
        $mem->set($this->get_pre_key(),time(),0);
    }
    public function get_by_where($where,$order='',$limit='0,2000',$fileds = '*') {
        
        if(!$this->is_cache) {
            return parent::get_by_where($where,$order,$limit);
        }
        
        $s = microtime(TRUE);
        $uncached = false;
        
        //首先按照键值排序
        ksort($where); //缓存更加能命中
        //找到in的情况
        foreach($where as $k=>$v) {
            $wh = explode(" ", $k);
            if($wh[1]=='in') {
                if(is_array($v)) {
                    $v = array_keys(array_flip($v));
                    $where[$k]  = $v;
                }
            }
        }
        $mem = RSF::get_instance()->get_memcache();
        $key = $this->get_pre_key().md5(serialize($where).$order.$limit);
        
        if($this->is_update($mem->get($key.'_save_time'))) {
            $uncached = true;
        }
        
        if(!$uncached) {
            $result = $mem->get($key.'_data');
            if(!$result){
                $ids = $mem->get($key);
                if(!$ids) {
                    $uncached = true;
                } else { 
                    $result = array();
                    foreach($ids as $id) {
                        $rowkey = $this->build_row_key($id);
                        $row = $mem->get($rowkey);
                        if(!$row) {
                            $uncached = true;
                            break;
                        }else {
                            $result[] = $row;
                        }
                    }
                }
            } else {
                RSF::get_instance()->debug('get direct result ','from Cache');
            }
        }
        if($uncached) {
            $result = parent::get_by_where($where,$order,$limit);
            $primary_keys = array();
            foreach($result as $row) {
                $primary_keys[] = $row[$this->get_pk_id()];
                $row_key = $this->build_row_key($row[$this->get_pk_id()]);
                $mem->set($row_key,$row,0);
            }
            $mem->set($key,$primary_keys,0);
            $mem->set($key.'_save_time',time(),0);
            $mem->set($key.'_data',$result,0);
        } else {
            RSF::get_instance()->debug($key.'  time taked:'.(microtime(true)-$s),'from cache');
        }
        return $result; 
    }
    
    public function get_by_ids($ids,$order='') {
        $mem = RSF::get_instance()->get_memcache();
        $cached_available  = true;
        if($order) { //不缓存了
            return parent::get_by_ids($ids,$order);
        } else {
            $result = array();
            foreach($ids as $id) {
                $rowkey = $this->build_row_key($id);
                $row = $mem->get($rowkey);
                if(!$row) {
                    $cached_available = FALSE;
                    break;
                }else {
                    $result[] = $row;
                }
            }
            if($cached_available) {
                return $result;
            } else {
                return parent::get_by_ids($ids,$order);
            }
        }
    }
    
    
    public function update_by_id($id,$data) {
        //清缓存
        $this->clear_row_cache($id);
        return parent::update_by_id($id,$data);
    }
    public function clear_row_cache($id) {
        $this->set_update();
        $mem = RSF::get_instance()->get_memcache();
        $key = $this->get_pre_key().$id;
        $mem->set($key,'',-1);
    }
    
    public function update_by_where($where,$data) {
        $result = $this->get_by_where($where);
        foreach($result as $k=>$v) {
            $this->update_by_id($v[$this->get_pk_id()], $data);
        }
        $this->set_update();
    }
    
    public function insert($data) {
        $this->set_update();
        return parent::insert($data);
    }
    
    public function del_by_id($id) {
        $mem = RSF::get_instance()->get_memcache();
        $this->set_update();
        //清缓存
        $key = $this->get_pre_key().$id;
        $mem->set($key,'',-1);
        return parent::del_by_id($id);
    }
    
    public function del_by_where($where) {
        $result = $this->get_by_where($where);
        foreach($result as $k=>$v) {
            $this->del_by_id($v[$this->get_pk_id()]);
        }
        return true;
    }
    
}
