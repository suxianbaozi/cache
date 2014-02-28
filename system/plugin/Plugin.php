<?php

rsf_require_view('View');

abstract class Plugin extends View{
	public $construct_data = array();
	final public function __construct($data=array()) {
	    RSF::get_instance()->debug("import plugin");
	    $this->construct_data = $data;
		$this->include_template($this->get_content(),'plugin');
	}
	public function get_construct_datas() {
		return $this->construct_data;
	}
	public function get_construct_data($key) {
		return $this->construct_data[$key];
	}
}


?>