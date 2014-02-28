<?php
rsf_require_plugin('Plugin');
class Common_AlertBoxPlugin extends Plugin {
    public function get_content() {
        
        return 'Common_AlertBox';
    }
    
    public static function get_css_list() {
        return array(
            'Common_AlertBox'
        );
    }
    public static function get_js_list() {
        return array(
            'Common_AlertBox'
        );
    }
}
