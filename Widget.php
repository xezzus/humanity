<?php
namespace humanity;

class Widget {

    public static $js;
    public static $css;
    private static $config;
    private static $app;
    private static $view;
    private static $routing;
    private static $property;
    private static $accept;
    private static $path;

    public function __construct(){
        # Config
        self::$config = (new Config)->get();
        # Url
        self::$path = explode('/',parse_url(urldecode($_SERVER['REQUEST_URI']))['path']);
        foreach(self::$path as $key=>$value){
            $value = trim($value);
            if(empty($value)) unset(self::$path[$key]);
        }
        self::$path = array_values(self::$path);
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
        # Accept
        self::$accept = (new Accept)->instance();
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
