<?php
namespace humanity;

class Js {

    private static $list = [];
    private static $host;
    private static $config;   

    public function __construct(){
        # Config
        self::$config = (new Config)->get();
        # Host
        self::$host = parse_url('http://'.$_SERVER['HTTP_HOST']);
        if(isset(self::$host['host'])) self::$host = self::$host['host'];
        else self::$host = '/';
    }

    public function add($js){
        if(!is_array($js)) $js = [$js];
        self::$list = array_merge(self::$list,$js);
    }

    public function get(){
        $js = array_unique(self::$list);
        $code = '';
        foreach($js as $name){
            $file = self::$config['core']['js'].'/'.$name.'.js';
            if(is_file($file)) $code .= file_get_contents($file);
        }
        $cache = self::$config['core']['js'].'/../cache/javascript.js';
        $minifier = new \MatthiasMullie\Minify\JS();
        $minifier->add($code);
        $code = $minifier->minify();
        file_put_contents($cache,$code);
        return '<script async src="http://'.self::$host.'/cache/javascript.js"></script>';
    }

    public function compile(){
        # ...
    }

}
?>
