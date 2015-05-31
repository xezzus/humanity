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
        if(!isset($this->config['api']['file'])) die('No API file');
        if(!isset($this->instance()->db)){
            $this->instance()->db = new db\SimplePdo('sqlite:'.$this->config['api']['file']);
            if(filesize($this->config['api']['file']) == 0){
                $dump = file_get_contents(__DIR__.'/api.sql');
                $this->instance()->db->connect->exec($dump);
            }
        }
        return $this->instance()->db;
    }

    public function authApp($appId,$sig){
        $select = "select 1 from app where id = ? and sig = ? limit 1";
        $find = $this->db()->select($select,[$appId,$sig])->fetch();
        if($find === false) return false;
        else return true;
    }

    public function isPermission($appId,$method){
        $select = "select 1 from permission where app_id = ? and method = ? limit 1";
        $find = $this->db()->select($select,[$appId,$method])->fetch();
        if($find === false) return false;
        else return true;
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

    public function findMethod($name){
        $query = "select status,params from method where name = ? limit 1";
        $find = $this->db()->select($query,[$name])->fetch();
        if($find) return $find;
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
