<?php
rsf_require_class('Request');
rsf_require_class('Response');

class RSF {
	public $request;
	private $debug_list = array();
	public function __construct() {
        set_error_handler("rsf_error_handler");
		$this->debug('rsf loaded');
	}
    
	public static $my;
	public static function get_instance() {
		if(!self::$my) {
			self::$my = new RSF();
		}
		return self::$my;
	}
	public function run() {
	    
	    RSF::get_instance()->debug($_SERVER,'server');
        RSF::get_instance()->debug($_REQUEST,'request');
        
		$time_start = microtime(1);
        
		$this->debug("request");
		if(!$this->request) {
			$this->request = new Request();
		}
		$this->debug("response");
		if(!$this->response) {
			$this->response = new Response();
		}
		
		$this->debug("get router");
		$uri = $this->request->get_uri_path();
		$this->debug($uri);
		$this->debug("get controller");
		$controller = $this->get_controller($uri);
        
        //开始执行拦截器，为嘛在这儿，因为是要在controller之前，而且需要用到request，用到response，so
        //第一种，默认的拦截器，所有的类都要,但是不能在except里
        $interceptor_config = $this->get_config('interceptor','interceptor');
        $default_interceptor = $interceptor_config['default'];
        if($default_interceptor) {
            foreach($default_interceptor as $interceptor) {
                if($exceptions = $interceptor_config['exception'][$interceptor]) {
                    if(in_array($controller, $exceptions)) {
                        continue;
                    }
                }
                rsf_require_interceptor($interceptor);
                $interceptor = $interceptor.'Interceptor';
                if(class_exists($interceptor)) {
                    $this->debug("run interceptor:".$interceptor);
                    $inter_obj = new $interceptor();
                    if($inter_obj->go_next()) {
                        continue;
                    } else {
                        $inter_obj->broken();
                        exit;
                    }
                } else {
                    continue;
                }
            }
        }
        
        //指定的拦截器
        $specialed_interceptor = $interceptor_config['specified'];
        if($intercepts = $specialed_interceptor[$controller]) {
            foreach($intercepts as $interceptor) {
                rsf_require_interceptor($interceptor);
                $interceptor = $interceptor.'Interceptor';
                if(class_exists($interceptor)) {
                    $this->debug("run interceptor:".$interceptor);
                    $inter_obj = new $interceptor();
                    if(!$inter_obj->go_next()) {
                        $inter_obj->broken();
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
            }
        }
        
        
		$this->debug($controller);
		rsf_require_controller($controller);
		$controller .= 'Controller';
		$ctl = new $controller();
		$this->debug("controller run");
        $c_s = microtime(1);
		$view = $ctl->run();
        $this->debug('Controller time taked:'.(microtime(1)-$c_s).'s');
		if($view) {
			rsf_require_view($view);
			$view_name = $view.'View';
			$view = new $view_name();
			$this->debug("build html");
			$view->build_container();	
		}
		$this->debug('All time taked:'.(microtime(1)-$time_start).'s');
		$this->show_debug_message();
	}
	public function setRequest($request) {
		$this->request = $request;
	}
	
	private function get_controller($uri) {
		$router = $this->get_config('router','router');
		
		foreach($router as $k=>$v) {
			foreach($v as $reg) {
				$reg = '/'.$reg.'/';
				if(preg_match_all($reg, $uri,$result)) {
					$matchs = array();
					foreach($result as $m) {
						foreach($m as $ma) {
							$matchs[] = $ma;
						}
					}
					$this->request->set_matchs($matchs);
					return $k;
				}
				
			}
		}
		return 'NotFound';
	}
	
	public function get_config($key,$file='common') {
		global $CONFIG_PATH;
		foreach($CONFIG_PATH as $k=>$v) {
			$config_path = $v.'/'.$file.'.php';
			if(file_exists($config_path)) {
				require $config_path;//局部变量，不能require_once噢
			}
		}
		return $config[$key];
	}
	public function setResponse($response) {
		$this->response = $response;
	}
	public function debug($str,$title='system') {
		$this->debug_list[] = array($str,$title);
	}
	
	public function show_debug_message() {
		if($this->request->is_debug) {
			$t_head = '<table border=1 style="font-size:12px; margin-top:20px; width:400px; margin:30px;">';
            $t_head.='<tr><td style="border:1px solid black" colspan=2>RSF Debug Message:</td></tr>';
            
            
			foreach($this->debug_list as $data) {
			    $str = $data[0];
			    if(is_array($str)) {
                    $str = '<pre>'.print_r($str,TRUE).'</pre>';
                }
                $title = $data[1];
				$t_head .= '<tr><td style="border:1px solid black;padding:0 5px 0 5px;">'.$title.'</td><td style="border:1px solid black"  >'.$str.'</td></tr>';
			}
			$t_end = '</table>';
			$t_head.=$t_end;
			echo $t_head;
		}
	}
    private $pdo_list = array();
    /**
     * 获取一个pdo实例
     * @param $config array
     * @return Object Pdo
     * */
    public function get_pdo($config) {
        $key = md5($config['db'].$config['host'].$config['port'].$config['user']);
        
        if($this->pdo_list[$key]) {
            return $this->pdo_list[$key];
        } else {
            $db_string = 'mysql:host='.$config['host'].';port='.$config['port'].';dbname='.$config['db'];
            $this->debug($db_string);
            try {
                $pdo  =  new PDO($db_string, $config['user'], $config['pass']);
            } catch  (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            $pdo->exec("SET CHARACTER SET utf8");
            $this->pdo_list[$key] = $pdo;
            return $pdo;
        }
    }
    /**
     * @desc 获取request对象
     * @return Object Request
     * */
    public function get_request() {
        return $this->request;
    }
    
    /**
     * @desc 获取request对象
     * @return Object Response
     * */
    public function get_response() {
        return $this->response;
    }
    
    private $memcache = null;
    /**
     * 获取memcache
     * @return Object memcache
     * */
    public function get_memcache() {
        rsf_require_class('MyMemcache');
        if(!$this->memcache) {
            $this->memcache = new MyMemcache();
            $domain = $this->get_config('memcache');
            $domains = explode(':',$domain);
            $this->memcache->connect($domains[0],intval($domains[1]));
        }
        return $this->memcache;
    }
    
    private $redis = null;
    /**
     * 获取memcache
     * @return Object memcache
     * */
    public function get_redis() {
        if(!$this->redis) {
            $this->redis = new Redis();
            $domain = $this->get_config('redis');
            $domains = explode(':',$domain);
            $this->redis->connect($domains[0],intval($domains[1]));
        }
        return $this->redis;
    }
    
}
?>
