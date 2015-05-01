<?php
namespace humanity\validators;

class Strings {

    public $string;

    public function __construct($string){
        $this->string = $string;
    }

    public function __call($name,$value){
        return false;
    }

    public function verify($type){
        return call_user_func([$this,$type]);
        return false;
    }

    public function name(){
        if(preg_match('/[^A-zА-я]{1,100}/',$this->string)) return false;
        else return true;
    }

    public function password(){
        $len = mb_strlen($this->string);
        if($len > 254 || $len < 8) return false;
        else return true;
    }

}
?>
