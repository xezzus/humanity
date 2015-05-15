<?php
namespace humanity;

class Validators {

    public $string;

    public function __construct($string){
        $this->string = $string;
    }

    public function __call($name,$value){
        return false;
    }

    public function integer(){
        if(preg_match('/^\d{1,15}$/',$this->string)) return true;
        else return false;
    }

    public function text(){
        if(preg_match('/^[\S\s]{1,10000}$/u',$this->string)) return true;
        else return false;
    }

    public function string(){
        if(preg_match('/^[\S ]{1,254}$/u',$this->string)) return true;
        else return false;
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
        if(preg_match('/^(\d{2}\.\d{2}\.\d{4}|\d{4}-\d{2}-\d{2})$/',$this->string)) return true;
        else return false;
    }

    public function stringMedium(){
        if(preg_match('/^[\S ]{1,127}$/u',$this->string)) return true;
        else return false;
    }

    public function stringSmall(){
        if(preg_match('/^[\S ]{1,63}$/u',$this->string)) return true;
        else return false;
    }

    public function stringTiny(){
        if(preg_match('/^[\S ]{1,31}$/u',$this->string)) return true;
        else return false;
    }

    public function emailorphone(){
        if($this->email() == true) return true;
        else if($this->phone() == true) return true;
        else return false;
    }

    public function boolean(){
        if(preg_match('/^(1|0|t|f|true|false|on|off)$/',$this->string)) return true;
        else return false;
    }

}
?>
