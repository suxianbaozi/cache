<?php
rsf_require_class('Bll_Web_Regions');
rsf_require_class('Bll_Web_Currency');

class WebRequest extends Request {
    private $user_id=0;
    private $region = 0;
    private $user_info = array();
    public function is_login() {
        return $this->user_id;
    }
    public function set_region($region) {
        $this->region = $region;
    }
    
    public function get_region() {
        return $this->region;
    }
    public function get_url(){
        return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
    public function get_currency() {
        $region_id = $this->get_region();
        $region_bll = new Bll_Web_Regions();
        $region_info  = $region_bll->get_region_by_id($region_id);
        $currency_id = $region_info['currency'];
        $currency_bll = new Bll_Web_Currency();
        $c = $currency_bll->get_currency_by_id($currency_id);
        return $c;
    }
    public function set_userid($user_id) {
        $this->user_id = $user_id;
    }
    public function get_userid() {
        return $this->user_id;
    }
    public function set_userinfo($user_info) {
        $this->user_info = $user_info;
    }
    public function get_userinfo() {
        return $this->user_info;
    }
    
    public function is_mobile() {
        $ua = $this->get_user_agent();
        $pattern1 = '/Profile\/MIDP-\d/i';
        $pattern2 = '/Mozilla\/.*(SymbianOS|iPhone|IEMobile|Android|Windows\sCE)/i';
        $isMobile = preg_match($pattern1, $ua) || preg_match($pattern2, $ua);
        if($isMobile) {
           return true;
        } else {
            return false;
        }
    }
    
}
