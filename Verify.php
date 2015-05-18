<?php
namespace humanity;

class Verify {

    public $value;

    public function __construct($value){
        $this->value = $value;
    }

    public function __get($name){
        if(!is_array($this->value)) $this->value = [$this->value];
        return  true xor in_array(false,array_map(function($value) use ($name){
            return call_user_func([(new Validators),$name],$value);
        },$this->value));
    }

}
?>
