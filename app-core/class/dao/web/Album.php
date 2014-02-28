<?php
rsf_require_class('Dao_CacheDao');
class Dao_Web_Album extends Dao_CacheDao {
    public function get_table_name () {
        return 'album';
    }
    public function get_pk_id() {
        return 'album_id';
    }
    public function get_db_name() {
        return 'reco_music';
    }
}
