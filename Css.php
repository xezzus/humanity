<?php
namespace humanity;

class Css {

    public $list = [];
    private static $instance = null;

    private function __clone(){
    }

    public function instance(){
        if(self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    public function add($css){
        $this->list[] = $css;
    }

    public function getList(){
        return $this->list;
    }

    public function compile(){
        # ...
    }

}
?>
