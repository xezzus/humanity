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

    public function name(){
        if(preg_match('/^\w{1,15}$/u',$this->string)) return true;
        else return false;
    }

    public function password(){
        $len = mb_strlen($this->string);
        if($len > 254 || $len < 8) return false;
        else return true;
    }

    public function phone(){
        if(preg_match('/^\+\d{2,20}$/',$this->string)) return true;
        else return false;
    }

    public function email(){
        if(filter_var($this->string,FILTER_VALIDATE_EMAIL)) return true;
        else return false;
    }

    public function date(){
        if(preg_match('/^\d{2}\.\d{2}\.\d{4}$/',$this->string)) return true;
        else return false;
    }

    public function smallString(){
        if(preg_match('/^\S{1,15}$/u',$this->string)) return true;
        else return false;
    }

}
?>
