<?php
namespace humanity;

class Application {

    private static $memory = [];
    private static $config;
    private static $application = [];

    public function __construct(){
        self::$config = (new Config)->get();
    }

    public function __get($name){
        array_push(self::$application,$name);
        return (new self);
    }

    public function __call($name,$value){
        array_push(self::$application,$name);
        $file = implode('/',self::$application);
        $file = self::$config['core']['apps'].'/'.$file.'.php';
        self::$application = [];
        if(is_file($file)) {
            $result = require($file);
            if(is_callable($result)) $result = call_user_func_array($result,$value);
            return $result;
        }
    }

}
?>
