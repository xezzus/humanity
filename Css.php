<?php
namespace humanity;

class Css {

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

    public function add($css){
        if(!is_array($css)) $css = [$css];
        $this->list = array_merge($this->list,$css);
    }

    public function get(){
        $css = array_unique($this->list);
        $css = array_map(function($css){
            $file = self::$config['paths']['css'].'/'.$css.'.css';
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
