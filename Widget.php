<?php
namespace humanity;

class Widget {

    public $config;
    private static $property;

    public function __construct(){
        # Application
        $this->app = new Application;
        # Js
        $this->js = (new Js)->instance();
        # Css
        $this->css = (new Css)->instance();
    }

    public function __get($name){
        self::$property= $name;
        return new self;
    }

    public function __call($name,$value){
        if(isset($value[0]) && !empty($value[0]) && is_array($value[0])) extract($value[0]);
        $file = (new Config)->instance()->config['paths']['widget'].'/'.self::$property.'/'.$name.'.phtml';
        if(is_file($file)) require($file);
    }

}
?>