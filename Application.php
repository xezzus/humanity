<?php
namespace humanity;

class Application {

    private static $singleton;
    private static $config;
    private static $name;
    private static $accept;

    public function __construct($name=null){
        self::$name = $name;
        self::$singleton = (new Singleton)->instance();
        self::$config = (new Config)->instance()->config;
        self::$accept = (new Accept)->instance();
    }

    public function __get($name){
        return new Application($name);
    }

    public function __call($name,$value){
        $file = self::$config['paths']['apps'].'/'.self::$name.'/'.$name.'.php'; self::$name = null;
        if(is_file($file)) {
            $result = require($file);
            if(is_callable($result)) $result = call_user_func_array($result,$value);
            return $result;
        }
    }

}
?>
