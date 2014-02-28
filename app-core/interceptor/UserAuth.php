<?php
rsf_require_interceptor('Interceptor');
rsf_require_class('GlobalFun');
rsf_require_class('Bll_Web_UserSearch');
class UserAuthInterceptor extends Interceptor {
    public function go_next() {
        $request = RSF::get_instance()->get_request();
        $user_id = $request->get_cookie('user_id');
        $token = $request->get_cookie('token');
        if($user_id && $token) {
            if(GlobalFun::sign($user_id)==$token) {
                $request->set_userid($user_id);
                //获取几个简单的user_info
                $user_bll = new Bll_Web_UserSearch();
                $user_info = $user_bll->get_user_by_userid($user_id);
                $request->set_userinfo($user_info);
            } else {
                $response = RSF::get_instance()->get_response();
                $response->set_cookie('user_id', '',-1,'/',RSF::get_instance()->get_config('domain'));
                $response->set_cookie('token', '',-1,'/',RSF::get_instance()->get_config('domain'));
                die('I just want to say hehe!');
            }
        }
        return true;
    }
}
