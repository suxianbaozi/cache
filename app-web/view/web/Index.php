<?php
rsf_require_view("view");
rsf_require_view("Frame");
class Web_IndexView extends FrameView {

	public function get_content(){
		$data = RSF::get_instance()->request->get_attributes();
		foreach($data as $k=>$v) {
			$this->set_data($k, $v);
		}
		
		return 'Web_Index';
	}
	
	public static function get_css_list() {
		return array_merge(parent::get_css_list(), 
		array(
			'Jplayer.blue.monday',
			'Jquery.bxslider',
			'Web_Index'
		));
	}
	
	public static function get_js_list() {
		return array_merge(
		parent::get_js_list(),
		array(
			//'Commonjs_DD.belatedPNG',
			'Jquery.jplayer.min',
			'Jquery.bxslider.min',
			'Web_Index',
			'Web_AddToPlayList',
			'Web_MusicList'
		));
	}
    
    
    public function get_title() {
        return 'RECO';
    }
}
