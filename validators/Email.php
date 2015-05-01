<?php
namespace humanity\validators;

class Email {

    public $string;

    public function __construct($string){
        $this->string = $string;
    }

    public function verify(){
        if(filter_var($this->string,FILTER_VALIDATE_EMAIL)) return true;
        else return false;
    }

}
?>
