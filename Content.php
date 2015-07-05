<?php
namespace humanity;

class Content {

    private static $host;
    private static $config;   
    private static $js;
    private static $css;
    private static $app;
    private static $view;
    private static $widget;
    private static $routing;

    public function __construct(){
        # Config
        self::$config = (new Config)->instance()->config;
        # Host
        self::$host = parse_url('http://'.$_SERVER['HTTP_HOST']);
        if(isset(self::$host['host'])) self::$host = self::$host['host'];
        else self::$host = '/';
        # Application
        self::$app = new Application;
        # View
        self::$view = new View;
        # Widget
        self::$widget = new Widget;
        # Js
        self::$js = (new Js)->instance();
        # Css
        self::$css = (new Css)->instance();
        # Routing
        self::$routing = new Routing;
    }

    public function view($name){
        $file = $this->file($name);
        if(is_file($file)) require $file;
    }

    public function page(){
        # get file
        $file = $this->file();
        # get content
        ob_start();
        require $file;
        $content = ob_get_clean();
        # get javascript
        $js = self::$js->getList();
        $js = array_unique($js);
        $js = array_map(function($js){
            $file = self::$config['paths']['js'].'/'.$js.'.js';
            if(is_file($file)) return '<script src="http://'.self::$host.'/js/'.$js.'.js"></script>';
            else return false;
        },$js);
        $js = implode("\n",$js);
        # get css
        $css = self::$css->getList();
        $css = array_unique($css);
        $css = array_map(function($css){
            $file = self::$config['paths']['css'].'/'.$css.'.css';
            if(is_file($file)) return '<link rel="stylesheet" href="http://'.self::$host.'/css/'.$css.'.css">';
            else return false;
        },$css);
        $css = implode("\n",$css);
        $content = preg_replace('/<\/head>/',"$css</head>",$content);
        $content = preg_replace('/<\/html>/',"$js</html>",$content);
        echo $content;
    }

    # $type - page,view
    public function file($name=false){
        if($name === false){
            $path = self::$config['paths']['page'];
        } else {
            $path = self::$config['paths']['view'].'/'.$name;
        }
        # Route
        $uri = $_SERVER['REQUEST_URI'];
        $uri = urldecode($uri);
        $uri = strtolower($uri);
        $uri = preg_replace("/[^a-z\d_\-\/]/",'',$uri);
        $uri = explode('/',$uri);
        foreach($uri as $key=>$value){ if(empty(trim($value))) { unset($uri[$key]); continue; } }
        $uri = array_values($uri);
        $isFile = null;
        $currentUri = null;
        $filePhtml = null;
        for($i=count($uri)-1; $i>=0; $i--){
          $nextUri = implode('/',array_slice($uri,0,$i+1));
          $getFile = function($type) use($path,$nextUri){
            $file = $path.'/'.$nextUri.'/index.'.$type;
            if(is_file($file)) return $file;
            return null;
          };
          $filePhtml = $getFile('phtml');
          if($filePhtml) { $currentUri = $nextUri; break; }
        }
        if($filePhtml === null) {
          $filePhtml = $path.'/index.phtml';
        }
        return $filePhtml;
    }

}
?>
