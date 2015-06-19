<?php
namespace humanity;

class Widget {

    public $config;
    private static $property;

    public function __construct(){
        # Application
        $this->app = new Application;
        # View
        $this->view = new View;
        # Js
        $this->js = (new Js)->instance();
        # Css
        $this->css = (new Css)->instance();
        # Routing
        $this->routing = new Routing;
    }

    public function __get($name){
        self::$property= $name;
        return new self;
    }

    public function __call($_name,$value){
        if(isset($value[0]) && !empty($value[0]) && is_array($value[0])) extract($value[0]);
        $file = (new Config)->instance()->config['paths']['widget'].'/'.self::$property.'/'.$_name.'.phtml';
        if(is_file($file)) require($file);
    }

}
?>
