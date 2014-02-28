<?php
rsf_require_class('Bll');
class Bll_Web_Album extends Bll {
	public function get_list(){
        $where = array(
            'album_id <'=>10
        );
        return $this->get_dao()->get_by_where($where);
    }

    public function get_by_id($id){
        $where = array(
            'album_id'=>$id
        );
        return $this->get_dao()->get_by_where($where);
    }

    public function update_desc_by_id($desc,$id) {
        $data = array(
            'desc'=>$desc,
        );
        $this->get_dao()->update_by_id($id,$data);
    }

    private function get_dao() {
        rsf_require_class('Dao_Web_Album');
        return new Dao_Web_Album();
    }
    
}
