<?php
namespace humanity;

class Cask {

    private static $instance = null;
    private static $space = [];

    private function __clone(){}

    public function instance(){
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function push($name,$value){
        self::$space[$name] = $value;
    }

    public function pull($name){
        return self::$space[$name];
    }

}
?>
