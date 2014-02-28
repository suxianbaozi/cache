<?php
rsf_require_interceptor('Interceptor');
/**
 * 模拟sessionid
 * */
class VersionInterceptor extends Interceptor {
    public function go_next() {
        $request = RSF::get_instance()->get_request();
        $response = RSF::get_instance()->get_response();
        
        $path = RSF::get_instance()->get_config('version_path');
        $version = '20130628';
        if($path) {
            $version = trim(file_get_contents($path));
        }
        define(VERSION, $version);
        
        return true;
    }
}