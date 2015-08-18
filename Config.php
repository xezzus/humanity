<?php
namespace humanity;

class Config {

    public static $config = [];

    public function __construct($config=[]){
        self::$config = array_merge(self::$config,$config);
    }    

    public function get(){
        return self::$config;
    }

}
?>
