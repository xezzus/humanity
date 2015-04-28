<?php
namespace humanity;

class Content {

  public $js = [];
  public $css = [];
  private $path;
  private $pathJs = __DIR__.'/../../../web/js/';
  private $pathCss = __DIR__.'/../../../web/css/';
  private $pathAssets = __DIR__.'/../../../web/assets/';

  public function __construct($path,$config=[]){
    # Config
    if(isset($config['pathJs']) && is_dir($config['pathJs'])) $pathJs = $config['pathJs'];
    if(isset($config['pathCss']) && is_dir($config['pathCss'])) $pathJs = $config['pathCss'];
    if(isset($config['pathAssets']) && is_dir($config['pathAssets'])) $pathJs = $config['pathAssets'];
    # Path
    $this->path = $path;
    # Application
    $this->app = new Application;
    # View
    $this->view = new View;
  }

  public function __destruct(){
  }

  public function load(){
    $path = $this->path.'/';
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
        $file = $path.$nextUri.'/index.'.$type;
        if(is_file($file)) return $file;
        return null;
      };
      $filePhtml = $getFile('phtml');
      $fileJs = $getFile('js');
      $fileCss = $getFile('css');
      if($filePhtml) { $currentUri = $nextUri; break; }
    }
    if($filePhtml === null) {
      $filePhtml = $path.'index.phtml';
      $fileJs = $path.'index.js';
      $fileCss = $path.'index.css';
    }
    /*
    # Create dir asset
    # Js files
    if(is_file($fileJs)) { $this->js[] = $fileJs; }
    $assets = $this->pathAssets.'js.json';
    touch($assets);
    $json = json_encode($this->js);
    file_put_contents($assets,$json);
    # Js files
    if(is_file($fileCss)) { $this->css[] = $fileCss; }
    $assets = $this->pathAssets.'css.json';
    touch($assets);
    $json = json_encode($this->css);
    file_put_contents($assets,$json);
    # Phtml file
    ob_start();
    */
    require($filePhtml);
    $content = ob_get_clean();
    unset($key,$value);
    return $content;
  }

}
?>
