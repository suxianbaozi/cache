<?php
rsf_require_class('GlobalFun');

abstract class View {
	public function get_title() {
	    $rsf = RSF::get_instance();
		return $rsf->get_config('name').$rsf->get_config('version');
	}
    public function get_keywords() {
        //return 'PHP,Frame,框架，好用';
    }
    
    public function get_description() {
        //return '世界上最好用的php框架';
    }
	
	public function build_container() {
	    RSF::get_instance()->debug('build_html_container:'.$this->get_container());
		$this->include_template($this->get_container());
	}
	
	public function build_content() {
	    RSF::get_instance()->debug('build_html_content');
		$this->include_template($this->get_content());
	}
	
	abstract public function get_content();
	
	public function get_container(){
		
	}
	
	public function include_template($name,$view='view',$type='phtml') {
	    extract($this->data);
        RSF::get_instance()->debug($name);
		$path = $this->get_real_path($name,$view,$type);
        if($path) {
            require $path;
        } else {
            RSF::get_instance()->debug("Not Found Template,Name:{$name},View:{$view},Type:{$type}","RSF ERROR");
        }
	}
    /**
     * 获取真正的路径
     * */
    public function get_real_path($name,$view='view',$type='phtml') {
        $path  = rsf_build_path($name,$view);
        $path = CUR_PATH.$path.'.'.$type;
        if(file_exists($path)) { 
            return $path;
        }
        
        $path  = rsf_build_path($name,$view);
        $path = SYS_PATH.$path.'.'.$type;
        if(file_exists($path)) { 
            return $path;
        }
        global $INCLUDE_PATH;
        if($INCLUDE_PATH) {
            foreach($INCLUDE_PATH as $path_root) {
                $path  = rsf_build_path($name,$view);
                $path = $path_root.$path.'.'.$type;
                if(file_exists($path)) { 
                    return $path;
                }
            }
        }
        return false;
    }
    public $data = array();
	public function set_data($key,$value) {
	    $this->data[$key] = $value;
	}
    public function get_class_name() {
        $called = get_called_class();
        return substr($called, 0, -4);
    }
	public function build_css_url() {
		$source_url = RSF::get_instance()->get_config("source");
		$location = RSF::get_instance()->get_config("location");
		$real_url = $source_url.$location.'/resource/css/'.$this->get_class_name().'.css';
		return $real_url.'?v='.VERSION;
	}
	
	public function build_js_url() {
		$source_url = RSF::get_instance()->get_config("source");
		$location = RSF::get_instance()->get_config("location");
		$real_url = $source_url.$location.'/resource/js/'.$this->get_class_name().'.js';
		return $real_url.'?v='.VERSION;
	}
	public static function get_css_list() {
		return array();
	}
	public static function get_js_list() {
		return array(
            'RSF'
        );
	}
    
    public static function get_static_js_list() {
        return array(
            
        );
    }
    public static function get_plugin() {
        return array();
    }
	
	public function get_css_content() {
	    $called = get_called_class();
		$css_list = $called::get_css_list();
		foreach($css_list as $v) {
			$this->include_template($v,'view','css');
		}
        
        //插件内的css
        $called = get_called_class();
        $plugin_list = $called::get_plugin();
        
        foreach ($plugin_list as $key => $plugin) {
            $real_name = $plugin.'Plugin';
            $css_list = $real_name::get_css_list();
            foreach($css_list as $v) {
                $this->include_template($v,'plugin','css');
            }
        }
	}
    
	public function get_js_content_header() {
	    $called = get_called_class();
        $common_js_list = $called::get_js_list();
        //插件内的js
        $called = get_called_class();
        $plugin_list = $called::get_plugin();
        $plugin_js_list = array();
        foreach ($plugin_list as $key => $plugin) {
            $real_name = $plugin.'Plugin';
            $js_list = $real_name::get_js_list();
            foreach($js_list as $v) {
                $plugin_js_list[] = $v;
            }
        }
        $key = '';
        $last_mod = 0;
        foreach($common_js_list as $v) {
            $real_path = $this->get_real_path($v,'view','js');
            $last_mod_time = filemtime($real_path);
            $key .= $v.$last_mod_time.'view';
            $last_mod = max($last_mod,$last_mod_time);
        }
        foreach($plugin_js_list as $v) {
            $real_path = $this->get_real_path($v,'plugin','js');
            $last_mod_time = filemtime($real_path);
            $key .= $v.$last_mod_time.'plugin';
            $last_mod = max($last_mod,$last_mod_time);
        }
        $key  = md5($key.'js');
        return array('etag'=>$key,'last_mod'=>$last_mod);
	}
	public function get_js_content() {
	    
	    $called = get_called_class();
        $common_js_list = $called::get_js_list();
        //插件内的js
        $called = get_called_class();
        $plugin_list = $called::get_plugin();
        $plugin_js_list = array();
        foreach ($plugin_list as $key => $plugin) {
            $real_name = $plugin.'Plugin';
            $js_list = $real_name::get_js_list();
            foreach($js_list as $v) {
                $plugin_js_list[] = $v;
            }
        }
        $key = '';
        foreach($common_js_list as $v) {
            $real_path = $this->get_real_path($v,'view','js');
            $last_mod_time = filemtime($real_path);
            $key .= $v.$last_mod_time.'view';
        }
        foreach($plugin_js_list as $v) {
            $real_path = $this->get_real_path($v,'plugin','js');
            $last_mod_time = filemtime($real_path);
            $key .= $v.$last_mod_time.'plugin';
        }
        $key  = md5($key.'js');
        if(RSF::get_instance()->get_config('cache_js')) {
            $mem  = RSF::get_instance()->get_memcache();
            $result = $mem->get($key);
            if($result) {
                RSF::get_instance()->get_response()->header('from_mem',1);
                echo $result;
                return;
            } else {
                $this->begain_script_block();
                foreach($common_js_list as $v) {
                    $this->include_template($v,'view','js');
                    echo ';';echo PHP_EOL;
                }
                foreach($plugin_js_list as $v) {
                    $this->include_template($v,'plugin','js');
                    echo ';';echo PHP_EOL;
                }
                $this->end_script_block();
                $content = implode('', $this->script_blocks);
                $url = RSF::get_instance()->get_config('cache_js_server');
                $post_data = array('js'=>$content);
                $c =  GlobalFun::post($url, $post_data);
                if(!$c) {
                    $c = $content;
                }
                $mem->set($key,$c,0);
                echo $c;
            }
        } else {
            foreach($common_js_list as $v) {
                $this->include_template($v,'view','js');
                echo ';';echo PHP_EOL;
            }
            foreach($plugin_js_list as $v) {
                $this->include_template($v,'plugin','js');
                echo ';';echo PHP_EOL;
            }
        }
	}
    //为了把script的执行代码全部放在最后，so
	public function begain_script_block() {
	    ob_start();
	}
    public function end_script_block() {
        $this->add_script_blocks(ob_get_contents());
        ob_end_clean();
    }
    private $script_blocks = array();
    public function add_script_blocks($str) {
        $this->script_blocks[] = $str;
    }
    public function write_script_blocks() {
        foreach($this->script_blocks as $v) {
            echo $v;
        }
    }
}
