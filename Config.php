<?php
namespace humanity;

class Config {

    private static $instance = null;
    public $config = null;

    private function __clone(){
        
    }

    public function instance(){
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function load($config){
        $this->config = $config;
    }

}
?>
