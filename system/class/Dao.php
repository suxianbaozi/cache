<?php
abstract class Dao {
    abstract public function get_table_name ();
    abstract public function get_pk_id();
    abstract public function get_db_name();
    public $pdo;
    public function __construct($db_type='slave') {
        $db_config = RSF::get_instance()->get_config('db','database');
        $cur_db_config = $db_config[$this->get_db_name()][$db_type];

        if($cur_db_config) {
            $this->pdo = RSF::get_instance()->get_pdo($cur_db_config);
        } else {
            return false;
        }
    }
    public function get_by_ids($ids,$order='') {
        $where = array(
            $this->get_pk_id().' in'=>$ids
        );
        return $this->get_by_where($where,$order);
    }
    
    public function get_by_id($id) {
        $sql = "select * from `".$this->get_table_name()."` where `".$this->get_pk_id()."`=?";
        RSF::get_instance()->debug($sql,'sql');
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array($id));
        $result =  $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public function my_implode($data) {
        $result = '';
        if(!$data) {
            return $result;
        }
        foreach($data as $v) {
            $result .= "'$v',";
        }
        return substr($result, 0,-1);
    }
    
    public function build_where($where) {
        $sql = " where ";
        if(!$where) {
            return array(
                'where'=>' ',
                'data'=>array()
            );
        }
        
        $data = array();
        $is_first = true;
        foreach($where as $k=>$v) {
            $wh = explode(" ", $k);
            $mod = $wh[1];
            if($wh[1]=='') {
                $mod = '=';
            } else if($wh[1]=='in'){
                $v = ' ('.$this->my_implode($v).')';
                $mod = 'in'.$v;
                $key = $wh[0];
                $sql.= (!$is_first?' and ':' ')."`{$key}` {$mod}";
                $is_first = FALSE;
                continue;
            } else if($wh[1]=='notin') {
                $v = ' ('.$this->my_implode($v).')';
                $mod = 'not in'.$v;
                $key = $wh[0];
                $sql.= (!$is_first?' and ':' ')."`{$key}` {$mod}";
                $is_first = FALSE;
            }
            $key = $wh[0];
            $sql.= (!$is_first?' and ':' ')."`{$key}` {$mod} ?";
            $data[] = $v;
            $is_first = FALSE;
        }
        $result = array(
            'where'=>$sql,
            'data'=>$data
        );
        return $result;
    }
    
    public function get_count_by_where($where) {
        $sql = "select count(*) as num from `" .$this->get_table_name(). "` ";
        $where_data = $this->build_where($where);
        $sql.=$where_data['where'];
        $data = $where_data['data'];
        $stmt = $this->pdo->prepare($sql);
        $result = array();
        $st = microtime(1);
        if($stmt->execute($data)) {
            $row =  $stmt->fetch(PDO::FETCH_ASSOC);
        }
        $spend = microtime(1)-$st;
        RSF::get_instance()->debug($sql.' Time taked:'.$spend,'sql');
        return $row['num'];
    }
    
    public function get_by_where($where,$order='',$limit='0,2000',$fileds = '*') {
        $sql = "select {$fileds} from `".$this->get_table_name()."` ";
        
        $where_data = $this->build_where($where);
        $sql.=$where_data['where'];
        $data = $where_data['data'];
        if($order) {
            $order = 'order by '.$order;
        }
        if($limit) {
            $limit = 'limit '.$limit;
        }
        $sql .= " {$order} {$limit}";
        
        $stmt = $this->pdo->prepare($sql);
        $result = array();
        
        //echo $sql;exit();
        $st = microtime(1);
        if($stmt->execute($data)) {
            while($row =  $stmt->fetch(PDO::FETCH_ASSOC)) { 
                $result[] = $row;
            }
        }
        $spend = microtime(1)-$st;
        RSF::get_instance()->debug($sql.' Time taked:'.$spend,'sql');
        return $result;
    }
    public function update_by_id($id,$data) {
        if(!is_array($data)) {
            trigger_error('$data必须是个数组');
            return;
        }
        
        $set_datas = array();
        if($data) {
            $set_string = ' set ';
            foreach($data as $k=>$v) {
                $set_datas[] = $v;
                $set_string.="`{$k}`=?,";
            }
            $set_string = substr($set_string, 0,-1);
        } else {
            return false;
        }
        $sql = "update `".$this->get_table_name()."` {$set_string}   where `".$this->get_pk_id()."`=?";
        RSF::get_instance()->debug($sql,'sql');
        $stmt = $this->pdo->prepare($sql);
        $set_datas = array_merge($set_datas,array($id));
        return $stmt->execute($set_datas);
    }
    public function update_by_where($where,$data) {
        $set_datas = array();
        if($data) {
            $set_string = ' set ';
            foreach($data as $k=>$v) {
                $set_datas[] = $v;
                $set_string.="`{$k}`=?,";
            }
            $set_string = substr($set_string, 0,-1);
        } else {
            return false;
        }
        
        $sql = "update `".$this->get_table_name()."` {$set_string} ";
        $to_data = array();
        $where = $this->build_where($where);
        $sql .= $where['where'];
        $to_data  = $where['data'];
        
        RSF::get_instance()->debug($sql,'sql');
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_merge($set_datas,$to_data));
    }
    public function insert($data) {
        $d_string = '';
        $insert_data = array();
        if($data) {
            $set_string = '';
            foreach($data as $k=>$v) {
                $set_string.="`{$k}`,";
                $d_string.="?,";
                $insert_data[] = $v;
            }
            $set_string = substr($set_string, 0,-1);
            $d_string = substr($d_string, 0,-1);
        } else {
            return false;
        }
        $sql = "insert into `".$this->get_table_name()."`({$set_string}) values($d_string)";
        //echo $sql;exit();
        RSF::get_instance()->debug($sql,'sql');
        $stmt = $this->pdo->prepare($sql);
        if(!$stmt->execute($insert_data)){
            $error = $stmt->errorInfo();
            throw new Exception('SQL ERROR:'.$sql.';INFO:'.$error[2]);
            return false;
        }
        return $this->pdo->lastInsertId();
    }
    
    public function exeSQL($sql) {
        RSF::get_instance()->debug($sql);
        $stmt = $this->pdo->prepare($sql);
        $result = array();
        if($stmt->execute()) {
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $result[] = $row;
            }
        }
        return $result;
    }
    
    public function del_by_id($id) {
        $sql = "delete from `".$this->get_table_name()."` where `".$this->get_pk_id()."`=?";
        RSF::get_instance()->debug($sql,'sql');
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array($id));
    }
    
    
    public function del_by_where($where) {
        $sql = "delete from `".$this->get_table_name()."` where 1=1 ";
        $data = array();
        foreach($where as $k=>$v) {
            $wh = explode(" ", $k);
            $mod = $wh[1]==''?'=':$wh[1];
            $key = $wh[0];
            $sql.=" and `{$key}`{$mod}?";
            $data[] = $v;
        }
        RSF::get_instance()->debug($sql,'sql');
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }
    
}
