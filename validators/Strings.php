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
    }

    public function name(){
        if(preg_match('/[^A-zА-я]{1,100}/',$this->string)) return false;
        else return true;
    }

}
?>
