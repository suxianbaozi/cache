<?php
rsf_require_plugin('Plugin');
class Common_PagesPlugin extends Plugin {
    public  $max_num = 10;
 	public function get_content() {
	    $pages = $this->get_construct_datas();
        $data = array();
        $data['page'] = $pages['page'];//当前页
        $data['pagesize'] = $pages['page_size'];
        $data['total_num'] = $pages['total_num'];
        $data['total_page'] = ceil($pages['total_num']/$pages['page_size']); 
        $data['base_url'] = $pages['base_url'];
        
        $left_min = max($pages['page']-3,1);
        $right_max = $left_min+7;
        $right_max = min($right_max,$data['total_page']);
        if($right_max==$data['total_page']) {
            $left_min = max(1,$right_max-7);
        }
        
        $data['left_min'] = $left_min;
        $data['right_max'] = $right_max;
        $this->set_data('pages', $data);
		return 'Common_Pages';
	}
    public function build_url($base_url,$page) {
        $base_url = trim($base_url,"&");
        if($page!=1) {
            if(strpos($base_url,'?')!==false) {
                return $base_url.='&page='.$page;
            } else {
                return $base_url.='?page='.$page;
            }
        } else {
            return $base_url;
        }
    }
    public static function get_css_list() {
        return array(
            'Common_Pages'
        );
    }
    public static function get_js_list() {
        return array(
            'Common_Pages'
        );
    }
}
