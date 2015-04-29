<?php
namespace humanity;

class Js {

    public static $list = [];
    private static $instance = null;

    private function __clone(){
    }

    public function instance(){
        if(self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    public function add($js){
        self::$list[] = $js;
    }

    public function getList(){
        return self::$list;
    }

    public function compile(){
        # ...
    }

}
?>
