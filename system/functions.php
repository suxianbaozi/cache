<?php

function get_real_class_name($class,$type) {
	if(strtolower($class)==$type) {
		$class = '';
	}
	if($type) {
		$class_true_name = $class.strtoupper(substr($type, 0,1)).substr($type, 1);
	} else {
		$class_true_name = $class;
	}
	return $class_true_name;
}

function rsf_require($class_name,$type='',$extend='php') {
	
	global $INCLUDE_PATH;
	$folder = $type==''?'class':$type;
	$build_path = rsf_build_path($class_name, $folder);
	
	$real_path = SYS_PATH.$build_path.'.'.$extend;
	
	
	$class_true_name = get_real_class_name($class_name,$type);

	if(file_exists($real_path)) {
		if(!class_exists($class_true_name)) {
			require_once $real_path;//包含类的话 ，就直接require_once了
		}
		return;
	}
	
	foreach($INCLUDE_PATH as $v) {
		$real_path = $v.$build_path.'.'.$extend;
		if(file_exists($real_path)) {
		    
			if(!class_exists($class_true_name)) {
				require_once $real_path;
			}
			return;
		}
	}
}

function rsf_require_class($class_name) {
	rsf_require($class_name);
}


function rsf_require_controller($class_name) {
	rsf_require($class_name,'controller');
}

function rsf_require_view($class_name) {
	rsf_require($class_name,'view');
}
function rsf_require_template($class_name) {
	rsf_require($class_name,'view','phtml');
}
function rsf_require_plugin($class_name) {
	rsf_require($class_name,'plugin');
}

function rsf_require_interceptor($class_name){
    rsf_require($class_name,'interceptor');
}

function rsf_build_path($class,$type) {
	$arr = explode('_', $class);
	$name = array_pop($arr);
	$path = '';
	foreach($arr as $v) {
		$path.='/'.strtolower($v);
	}
	return '/'.$type.$path.'/'.$name;
}

function rsf_error_handler($error_no){
    if($error_no!=8) {
        //debug_print_backtrace();
    }
}

?>