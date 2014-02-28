<?php
rsf_require_class('Plugin');
rsf_require_class('Bll_Admin_Ads');

class AdPlugin extends  Plugin{
    public function get_content() {
        $ad_id = $this->get_construct_data('id');
        $ad_bll = new Bll_Admin_Ads();
        $ad = $ad_bll->get_ad_by_id($ad_id);
        $this->set_data('content', $ad['content']);
        return 'Ad';
    }
}
