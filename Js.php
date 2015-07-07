<?php
namespace humanity;

class Js {

    public $list = [];
    private static $instance = null;
    private static $host;
    private static $config;   

    public function __construct(){
        # Config
        self::$config = (new Config)->instance()->config;
        # Host
        self::$host = parse_url('http://'.$_SERVER['HTTP_HOST']);
        if(isset(self::$host['host'])) self::$host = self::$host['host'];
        else self::$host = '/';
    }

    private function __clone(){
    }

    public function instance(){
        if(self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    public function add($js){
        if(!is_array($js)) $js = [$js];
        $this->list = array_merge($this->list,$js);
    }

    public function get(){
        $js = array_unique($this->list);
        $js = array_map(function($js){
            $file = self::$config['paths']['js'].'/'.$js.'.js';
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
