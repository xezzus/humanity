<?php
namespace humanity;

class Content {

  public $js;
  public $css = [];
  private $path;
  private $content;

  public function __construct($path){
    # Type
    $this->path = $path;
    # Config
    $this->config = (new Config)->instance()->config;
    # Application
    $this->app = new Application;
    # View
    $this->view = new View;
    # Widget
    $this->widget = new Widget;
    # Js
    $this->js = (new Js)->instance();
    # Css
    $this->css = (new Css)->instance();
  }

  public function load(){
    $path = $this->path;
    # Route
    $uri = explode('/',$_SERVER['REQUEST_URI']);
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
    ob_start();
    require($filePhtml);
    $content = ob_get_clean();
    unset($key,$value);
    $this->content = $content;
  }

  public function getContent(){
      return $this->content;
  }

  public function getJs(){
      $js = $this->js->getList();
      $js = array_unique($js);
      $js = array_map(function($js){
          return '<script src="'.$js.'"></script>';
      },$js);
      $js = implode("\n",$js);
      return $js;
  }

  public function getCss(){
      $css = $this->css->getList();
      $css = array_unique($css);
      $css = array_map(function($css){
          return '<link rel="stylesheet" href="'.$css.'">';
      },$css);
      $css = implode("\n",$css);
      return $css;
  }

}
?>
