<?php
namespace humanity;

class Validators {


    public function __call($name,$value){
        return false;
    }

    public function integer($string){
        if(preg_match('/^\d{1,15}$/',$string)) return true;
        else return false;
    }

    public function text($string){
        if(preg_match('/^[\S\s]{1,10000}$/u',$string)) return true;
        else return false;
    }

    public function string($string){
        if(preg_match('/^[\S ]{1,254}$/u',$string)) return true;
        else return false;
    }

    public function name($string){
        if(preg_match('/^\w{1,15}$/u',$string)) return true;
        else return false;
    }

    public function password($string){
        $len = mb_strlen($string);
        if($len > 254 || $len < 8) return false;
        else return true;
    }

    public function phone($string){
        if(preg_match('/^\+\d{2,20}$/',$string)) return true;
        else return false;
    }

    public function email($string){
        if(filter_var($string,FILTER_VALIDATE_EMAIL)) return true;
        else return false;
    }

    public function date($string){
        if(preg_match('/^(\d{2}\.\d{2}\.\d{4}|\d{4}-\d{2}-\d{2})$/',$string)) return true;
        else return false;
    }

    public function stringMedium($string){
        if(preg_match('/^[\S ]{1,127}$/u',$string)) return true;
        else return false;
    }

    public function stringSmall($string){
        if(preg_match('/^[\S ]{1,63}$/u',$string)) return true;
        else return false;
    }

    public function stringTiny($string){
        if(preg_match('/^[\S ]{1,31}$/u',$string)) return true;
        else return false;
    }

    public function emailorphone($string){
        if($this->email($string) == true) return true;
        else if($this->phone($string) == true) return true;
        else return false;
    }

    public function boolean($string){
        if(preg_match('/^(1|0|t|f|true|false|on|off)$/',$string)) return true;
        else return false;
    }

}
?>
