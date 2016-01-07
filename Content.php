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
        self::$uri = new Uri;
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
        # get css
        $css = self::$css->get();
        # get css
        $js = self::$js->get();
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

    public function js($query){
        echo self::$js->compile($query);
    }

    # $type - page,view
    public function file($name=false){
        if($name === false){
            $path = self::$config['core']['page'];
        } else {
            $path = self::$config['core']['view'].'/'.$name;
        }
        # Route
        $uri = self::$uri->arr();
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
