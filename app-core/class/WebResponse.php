<?php
rsf_require_class('Response');
rsf_require_class('UrlBuilder');
class WebResponse extends Response{
    public function not_found() {
        Header("HTTP/1.1 404 Not Found");
        //echo '<html><body align="center"></body></html>';
        return "Web_NotFound";
    }
}
