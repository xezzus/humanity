<?php
namespace humanity;

class Config {

    private static $instance = null;

    private function __clone(){
        
    }

    public function instance(){
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get(){
        if(!isset($this->config)){
            $this->config = __DIR__.'/../../../config/main.php';
            $this->config = require($this->config);
        }
        return $this->config;
    }
}
?>
