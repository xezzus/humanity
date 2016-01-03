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
        $code = '';
        foreach($css as $name){
            $file = self::$config['core']['css'].'/'.$name.'.css';
            if(is_file($file)) $code .= file_get_contents($file);
        }
        $minifier = new \MatthiasMullie\Minify\CSS();
        $minifier->add($code);
        $code = $minifier->minify();
        return "<style>$code</style>";
    }

    public function compile(){
        # ...
    }

}
?>
