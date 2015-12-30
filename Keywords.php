<?php
namespace humanity;

class Keywords {

    private static $memory = [];

    public function add($title){
        if(!is_array($title)) $title = [$title];
        self::$memory = array_merge(self::$memory,$title);
        return $this;
    }

    public function get(){
        self::$memory = array_reverse(self::$memory);
        foreach(self::$memory as $key=>$value){
            $value = urldecode($value);
            self::$memory[$key] = $value;
        }
        return '<meta name="keywords" content="'.implode(',',self::$memory).'">';
    }

}
?>
