<?php

class Response {
	
	final public function __construct() {
		$this->set_debug();
	}
	
	final private function set_debug() {
		$params = RSF::get_instance()->request->get_params();
		if($params['debug']) {
			$this->set_cookie('debug',1);
		} else if(isset($params['debug'])){
			$this->set_cookie('debug', 0, time()-1);
		}
	} 
	
	public function not_found() {
		Header("HTTP/1.1 404 Not Found");
        return false;
	}
    public function status_500() {
        Header("HTTP/1.1 500 Invalid Params");
        echo '<h1>非法参数</h1>';
    }
	public function set_cookie($key,$v,$t=0,$path='/',$domain='') {
		setcookie($key,$v,$t,$path,$domain);
	}
	public function header($key,$value) {
		Header("$key:$value");
	}
    public function redirect($location) {
        $this->header("Location", $location);
    }
}
