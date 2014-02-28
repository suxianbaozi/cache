<?php
rsf_require_interceptor('Interceptor');
class RegionSetInterceptor extends Interceptor {
    public function go_next() {
        $request = RSF::get_instance()->get_request();
        $domain = $request->get_domain();
        $domains = explode('.', $domain);
        $domain_extend = array_pop($domains);
        
        $region_domain = RSF::get_instance()->get_config('region_domain');
        if($region_domain[$region]) {
            $request->set_region($region_domain[$region]);
        } else {
            $request->set_region(1);
        }
        return true;
    }
}
