<?php
namespace humanity\db;

class SimplePdo {

    public $connect;
    
    public function __construct($dsn,$user=null,$pass=null){
        $this->connect = new \PDO($dsn,$user,$pass);
        $this->connect->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    public function select($query,$params=null){
        $query = $this->connect->prepare($query);
        $query->execute($params);
        return $query;
    }

    public function insert($table,$params,$returning=null){
        $fields = implode(',',array_keys($params));
        $prepare = implode(',',array_map(function($field){ return '?'; },$params));
        $values = array_values($params);
        $values = array_map(function($value){
            if(is_array($value)) return json_encode($value);
            else return $value;
        },$values);
        if(!empty($returning)){
            if(is_array($returning)) $returning = implode(',',$returning);
            $returning = ' returning '.$returning;
        }
        $query = "insert into \"$table\" ($fields) values ($prepare)$returning";
        $query = $this->connect->prepare($query);
        if($query->execute($values) === true) return $query;
        else return false;
    }

    public function error(){
        return $this->connect->errorInfo();
    }

}
?>
