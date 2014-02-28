<?php
rsf_require_interceptor('Interceptor');
/**
 * 模拟sessionid
 * */
class GuidInterceptor extends Interceptor {
    public function go_next() {
        $request = RSF::get_instance()->get_request();
        $response = RSF::get_instance()->get_response();
        $guid = $request->get_cookie('guid');
        if(!$guid) {
            $guid = md5(microtime(1).$request->get_user_agent().$request->get_client_ip());
            $domain = RSF::get_instance()->get_config('domain');
            $response->set_cookie('guid', $guid,time()+100000,'/',$domain);
        }
        RSF::get_instance()->debug($guid);
        $request->set_guid($guid);
        return true;
    }
}