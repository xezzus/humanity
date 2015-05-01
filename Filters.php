<?php
namespace humanity;

class Filters {

    public $string;

    public function __construct($string){
        $this->string = $string;
    }

    public function stripTags(){
        $this->string = preg_replace('/<[\s\S]*>[\s\S]*<\/[\s\S]*>/','',$this->string);
    }

}
?>
