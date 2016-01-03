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
    private static $title;
    private static $description;
    private static $keywords;
    private static $uri;

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
        self::$js = new Js;
        # Css
        self::$css = new Css;
        # Title
        self::$title = new Title;
        # Desctiption
        self::$description = new Description;
        # Keywords
        self::$keywords = new Keywords;
        # Uri
        self::$uri = explode('/',$_SERVER['REQUEST_URI']);
        foreach(self::$uri as $key=>$value){
            if(empty($value)) unset(self::$uri[$key]);
            else self::$uri[$key] = urldecode($value);
        }
        self::$uri = array_values(self::$uri);
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
        # get title
        $title = self::$title->get();
        # get description
        $description = self::$description->get();
        # get keywords
        $keywords = self::$keywords->get();
        # include for page
        $content = preg_replace('/<head>/',"<head>$keywords",$content);
        $content = preg_replace('/<head>/',"<head>$description",$content);
        $content = preg_replace('/<head>/',"<head>$title",$content);
        $content = preg_replace('/<\/head>/',"$css</head>",$content);
        $content = preg_replace('/<\/body>/',"$js</body>",$content);
        $minify = new MinifyHTML($content);
        $content = $minify->compress();
        echo $content;
    }

    # $type - page,view
    public function file($name=false){
        if($name === false){
            $path = self::$config['core']['page'];
        } else {
            $path = self::$config['core']['view'].'/'.$name;
        }
        # Route
        $uri = self::$path;
        array_push($uri,'');
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
