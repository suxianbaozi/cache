<?php
abstract class Controller {
	abstract public function run();
    
    public function router_action($handle='action'){
        $request = RSF::get_instance()->get_request();
        $params = $request->get_params();
        $action = $params[$handle];
        $action = $action?$action:'index';
        if(method_exists($this, $action)) {
            $result = $this->$action($params,$request);
        }
        return $result;
    }
}
