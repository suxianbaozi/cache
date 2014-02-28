<?php
rsf_require_class('Url_Album');
rsf_require_class('Url_Singer');
class UrlBuilder{
    public static function build_static($path) {
        $static = RSF::get_instance()->get_config('static');
        return $static.'/'.$path;
    }
    
    public static function build_image_url($key,$width=230,$height=230) {
        $key = $key?$key:'10e474c0e9bdb41179a796e2d83c289d';
        $static = RSF::get_instance()->get_config('file_url');
        return $static.'/img/'.$key."/{$width}x{$height}.jpg";
    }
    
    public static function build_user_image($key,$width=230,$height=230) {
    	$key = $key?$key:'194a0eb092a5c583fb5a6d5f861588bd';
    	$static = RSF::get_instance()->get_config('file_url');
    	return $static.'/img/'.$key."/{$width}x{$height}.jpg";
    }
    public static function build_raise_image($key,$width=80,$height=80) {
        if($key) { 
            $static = RSF::get_instance()->get_config('file_url');
            return $static.'/img/'.$key."/{$width}x{$height}.jpg";
        } else {
            return self::build_static('/images/funding/notUploadCover.png');
        }
    }
    public static function build_program_stream_url($program,$week='') {
        $week = $week?$week:'%d';
        $base_url = RSF::get_instance()->get_config("hls_url");
        return "{$base_url}/{$program['id']}_{$week}.aac";
    }
}
