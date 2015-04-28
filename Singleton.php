<?php
namespace humanity;

class Singleton {

    protected static $instance = null;

    private function __clone(){
    }

    public function instance(){
        if(self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

}
?>
