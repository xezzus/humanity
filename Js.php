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
        $js = array_map(function($js){
            $file = self::$config['core']['js'].'/'.$js.'.js';
            if(is_file($file)) return '<script src="http://'.self::$host.'/js/'.$js.'.js"></script>';
            else return false;
        },$js);
        $js = implode("\n",$js);
        return $js;
    }

    public function compile(){
        # ...
    }

}
?>
