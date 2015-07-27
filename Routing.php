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

    public function isUri($uri,$func=null){
        $uri = $this->explodeUri($uri);
        if($this->uri == $uri) {
            if(is_callable($func)) $func();
            else if(is_string($func)) echo $func;
        }
        return $this;
    }

    public function path(){
        $args = func_get_args();
        foreach($args as $key=>$arg){
            if(is_callable($arg) && isset($this->uri[$key])) {
                $arg($this->uri[$key]);
            }
        }
    }

}
?>
