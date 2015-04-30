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
        if(!is_array($js)) $js = [$js];
        $this->list = array_merge($this->list,$js);
    }

    public function getList(){
        return $this->list;
    }

    public function compile(){
        # ...
    }

}
?>
