<?php
namespace humanity;

class Accept {
    
    protected static $instance = null;

    private function __clone(){
    }

    public function instance(){
        if(self::$instance === null) {
            $accept = explode(',',$_SERVER['HTTP_ACCEPT']);
            foreach($accept as $key=>$value){
                $value = explode('/',$value);
                $value[1] = explode(';',$value[1]);
                $this->{$value[0]}[array_shift($value[1])] = array_map(function($value){ return explode('=',$value); },$value[1]);
            }
            self::$instance = $this;
        }
        return self::$instance;
    }

}
?>
