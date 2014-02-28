<?php
class MyMemcache extends Memcache {
    public function set($key,$value,$t=86400) {
        $cache = RSF::get_instance()->get_config('cache');
        if(!$cache) {
            return false;
        }
        parent::set($key,$value,MEMCACHE_COMPRESSED,$t);
    }
    public function get($key) {
        $cache = RSF::get_instance()->get_config('cache');
        if(!$cache) {
            return false;
        }
        return parent::get($key);
    }
}
