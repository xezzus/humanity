<?php
namespace humanity\db;

class SimplePdo {

    public $connect;
    
    public function __construct($dsn,$pass=null,$base=null){
        $this->connect = new \PDO($dsn,$user,$pass);
        $this->connect->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    public function select($query,$params){
        $query = $this->connect->prepare($query);
        $query->execute($params);
        return $query;
    }

    public function insert($table,$params){
        $fields = implode(',',array_keys($params));
        $prepare = implode(',',array_map(function($field){ return '?'; },$params));
        $values = array_values($params);
        $query = "insert into \"$table\" ($fields) values ($prepare)";
        $query = $this->connect->prepare($query);
        return $query->execute($values);
    }

    public function error(){
        return $this->connect->errorInfo();
    }

}
?>
