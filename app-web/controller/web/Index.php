<?php
rsf_require_controller('Controller');
rsf_require_class('Bll_Web_Album');
class Web_IndexController extends Controller {
	public function run() {
		
		$request = RSF::get_instance()->request;
		$params = $request->get_params();
		$matchs = $request->get_matchs();
        $this->router_action();
		return false;
	}

    public function  get_list($params,$request) {
        $bll = new Bll_Web_Album();
        echo '<pre>';
        print_r($bll->get_list());
    }

    public function  get_one($params,$request) {
        $bll = new Bll_Web_Album();
        echo '<pre>';
        print_r($bll->get_by_id(1));

    }

    public function  update($params,$request) {
        $bll = new Bll_Web_Album();
        echo '<pre>';
        print_r($bll->update_desc_by_id('我去',1));
    }
}
