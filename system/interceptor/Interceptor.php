<?php

abstract class Interceptor {
    public function __construct() {
        
    }
    abstract public function go_next();
    public function broken() {
        RSF::get_instance()->get_response()->status_500();
        exit;
    }
}
