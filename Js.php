<?php
namespace humanity;

class Js {

    public $list = [];
    private static $instance = null;

    private function __clone(){
    }

    public function instance(){
        if(self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    public function add($js){
        $this->list[] = $js;
    }

    public function compile(){
        # ...
    }

}
?>
