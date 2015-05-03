<?php
namespace humanity;

class Api {

    private $db;
    private static $instance;
    private $config;

    public function __construct(){
        $this->config = (new Config)->instance()->config;
    }

    public function instance(){
        if(!self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function db(){
        if(!isset($this->instance()->db)){
            $this->instance()->db = new db\SimplePdo('sqlite:'.$this->config['api']['file']);
            if(filesize($this->config['api']['file']) == 0){
                $dump = file_get_contents(__DIR__.'/api.sql');
                $this->instance()->db->connect->exec($dump);
            }
        }
        return $this->instance()->db;
    }

    public function AddMethod($name,$status=null){
        $insert['name'] = $name;
        if($status) $insert['status'] = $status;
        return $this->db()->insert('method',$insert);
    }

    public function ListMethod($page=0){
        $limit = 35;
        $offset = (($page < 0) ? 0 : $page)*$limit;
        $find = $this->db()->select('select name,status from method limit '.$limit.' offset '.$offset)->fetchAll();
        return array_column($find,'status','name');
    }

    public function findStatusMethod($name){
        $query = "select status from method where name = ? limit 1";
        $find = $this->db()->select($query,[$name])->fetch();
        if($find) return $find['status'];
    }

    public function DelMethod(){
    }

    public function ChangeStatusMethod(){
    }

}
?>