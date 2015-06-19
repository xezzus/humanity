<?php
namespace humanity;

class Routing {

    public $uri;

    public function __construct(){
        $this->uri = $this->explodeUri($_SERVER['REQUEST_URI']);
    }

    public function explodeUri($uri){
        $uri = explode('/',$uri);
        foreach($uri as $key=>$value){
            $value = trim($value);
            if(empty($value)) unset($uri[$key]);
        }
        $uri = array_values($uri);
        return $uri;
    }

    public function isUri($uri,$func){
        $uri = $this->explodeUri($uri);
        if($this->uri == $uri) $func();
        return $this;
    }

}
?>
