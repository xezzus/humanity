<?php
namespace humanity;

class Css {

    public static $list = [];
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

    public function add($css){
        if(!is_array($css)) $css = [$css];
        self::$list = array_merge(self::$list,$css);
    }

    public function get(){
        $css = array_unique(self::$list);
        $css = array_map(function($css){
            $file = self::$config['core']['css'].'/'.$css.'.css';
            if(is_file($file)) return '<link rel="stylesheet" href="http://'.self::$host.'/css/'.$css.'.css">';
            else return false;
        },$css);
        $css = implode("\n",$css);
        return $css;
    }

    public function compile(){
        # ...
    }

}
?>
