<?php
namespace humanity;

class Accept {
    
    protected static $instance = null;

    private function __clone(){
    }

    public function instance(){
        if(self::$instance === null) {
            if(!isset($_SERVER['HTTP_ACCEPT'])) $_SERVER['HTTP_ACCEPT'] = '*/*';
            $accept = explode(',',$_SERVER['HTTP_ACCEPT']);
            foreach($accept as $key=>$value){
                $value = explode('/',$value);
                $value[1] = explode(';',$value[1]);
                $name = array_shift($value[1]);
                $data = [];
                foreach($value[1] as $k=>$v){
                    $v = explode('=',$v);
                    $data[$v[0]] = $v[1];
                }
                $this->{$value[0]}[$name] = $data;
            }
            self::$instance = $this;
        }
        return self::$instance;
    }

}
?>
