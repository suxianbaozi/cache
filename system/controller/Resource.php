<?php

rsf_require_controller("Controller");

class ResourceController extends Controller {
    public function run() {
        $response = RSF::get_instance() -> response;
        $matchs = RSF::get_instance() -> request -> get_matchs();
        $type = $matchs[2];
        $class = $matchs[3];
        $content_type = $type == 'css' ? 'text/css' : 'application/x-javascript';
        RSF::get_instance() -> response -> header('Content-Type', $content_type. '; charset=utf-8');
        rsf_require_view($class);
        $class_name = $class . 'View';
        
        if(!class_exists($class_name)) {
            return false;
        }
        
        $view = new $class_name();
        if ($type == 'css') {
            $response->header('Cache-control','max-age=1000');
            echo $view -> get_css_content();
        } else if ($type == 'js') {
            $head = $view -> get_js_content_header();
            $etag = $head['etag'];
            $last_mod = $head['last_mod'];
            $response -> header('ETag', $etag);
            $response -> header('Last-Modified', gmdate('D, d M Y H:i:s', $last_mod) . ' GMT');
            if ((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $last_mod) 
            || (isset($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) < $last_mod) 
            || (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag)) {
                header("HTTP/1.1 304 Not Modified");
                exit(0);
            } else {
                echo "/*\n\n\n\n";
                echo 'Honey,Is code looks well ?';
                echo "\n\n\n\n*/";
                echo $view -> get_js_content();
            }
        }
    }

}
