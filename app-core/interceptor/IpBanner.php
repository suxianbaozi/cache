<?php
rsf_require_interceptor('Interceptor');
class IpBannerInterceptor extends Interceptor {
    public function go_next() {
        $request = RSF::get_instance()->get_request();
        $response = RSF::get_instance()->get_response();
        $ip = $request->get_client_ip();
        return true;
    	if(strpos($ip, '192.168')!==false) {
                return true;
            }
            if($ip=='203.110.174.151') {
                return true;
            }
            if($ip=='127.0.0.1') {
                return true;
            }
            
            if($ip=='58.33.92.173') {
                return true;
    	}
        return FALSE;
    }
    
    public function broken() {
        echo 'You are not allowed!';
        exit;
    }
}
