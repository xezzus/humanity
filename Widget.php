<?php
namespace humanity;

class Widget {

    private static $config;
    private static $js;
    private static $css;
    private static $app;
    private static $view;
    private static $routing;
    private static $property;

    public function __construct(){
        # Config
        self::$config = (new Config)->instance()->config;
        # Application
        self::$app = new Application;
        # View
        self::$view = new View;
        # Js
        self::$js = (new Js)->instance();
        # Css
        self::$css = (new Css)->instance();
        # Routing
        self::$routing = new Routing;
    }

    public function __get($name){
        self::$property= $name;
        return new self;
    }

    public function __call($_name,$value){
        if(isset($value[0]) && !empty($value[0]) && is_array($value[0])) extract($value[0]);
        $file = self::$config['paths']['widget'].'/'.self::$property.'/'.$_name.'.phtml';
        if(is_file($file)) require($file);
    }

}
?>
