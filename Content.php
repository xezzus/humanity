<?php
namespace humanity;

class Content {

    private static $host;
    private static $path;
    private static $config; 
    private static $js;
    private static $css;
    private static $app;
    private static $view;
    private static $widget;
    private static $routing;
    private static $accept;

    public function __construct(){
        # Config
        self::$config = (new Config)->get();
        # Host
        self::$host = parse_url('http://'.$_SERVER['HTTP_HOST'])['host'];
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
        # Widget
        self::$widget = new Widget;
        # Js
        self::$js = (new Js)->instance();
        # Css
        self::$css = (new Css)->instance();
        # Routing
        self::$routing = new Routing;
        # Accept
        self::$accept = (new Accept)->instance();
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
        $js = self::$js->get();
        # get css
        $css = self::$css->get();
        # include for page
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
