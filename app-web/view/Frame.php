<?php
rsf_require_plugin("Web_Header");
rsf_require_plugin("Web_TopNavi");
rsf_require_class("UrlBuilder");
rsf_require_plugin("Web_Footer");
rsf_require_plugin("Common_AlertBox");
rsf_require_plugin('Ad');
rsf_require_class('Url_Album');
rsf_require_class('Url_Singer');
abstract class FrameView extends View {
	
    
    
	public function get_container() {
		return 'Frame';
	}
	public static function get_css_list() {
		return array(
			'Frame',
			'Jscrollpane',
			'Style'
		);
	}
	public static function get_js_list() {
		return array_merge(
        parent::get_js_list(),array(
		    'Style',
		    //'Jquery.leanModal.min',
		    //'Addsong',
		    'Jquery.json-2.4.min',
		    'Mousewheel',
		    'Jscrollpane',
			'Frame',
			'Commonjs_User',
			'Commonjs_Jquery-ui-1.10.2.custom.min',
			'Commonjs_PlayerApi',
			'Commonjs_Util',
			'Commonjs_Ie.placeholder'
		));
	}
    public static function get_static_js_list() {
        return array_merge(
        parent::get_static_js_list(),array(
            'js/jquery-1.7.1.min.js',
        ));
    }
    public static function get_plugin() {
        return array(
            'Web_Header',
            'Web_UserStatus',
            'Common_AlertBox'
        );
    }
    
    public function get_keywords() {
        return '正版音乐,独立音乐人,同步歌词,无损音质,320K MP3,flac,专辑插图,免费试听';
    }
    
    public function get_description() {
        return 'RECO是一个正版音乐发布平台，让艺术家获益，让乐迷欣赏和支持优秀的作品。加入RECO,你可以获得最新最详细的专辑信息，最全的同步歌词，最丰富的专辑插图和最高质的无损音乐';
    }
    
    
    public function get_main_path() {
        return '';
    }
}
