<?php
rsf_require_interceptor('Interceptor');
class BrowserInterceptor extends Interceptor {
    public function go_next() {
        $request = RSF::get_instance()->get_request();
        $response = RSF::get_instance()->get_response();
        $user_agent = $request->get_user_agent();
        if(strpos($user_agent, 'MSIE 6.0')!==false) {
            $response->redirect(Url_CommonUrl::build_music_url('/advice/'));
            return false;
        }
        
        if(strpos($user_agent, 'MSIE 7.0')!==false) {
            $response->redirect(Url_CommonUrl::build_music_url('/advice/'));
            return false;
        }
        return TRUE;
    }
    
    public function broken() {
        echo 'You are not allowed!';
        exit;
    }
}
