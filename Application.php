<?php
namespace humanity;

class Application {

  private $name;
  private $singleton;
  private $config;

  public function __construct($name=''){
      $this->name = $name;
      $this->singleton = (new Singleton)->instance();
      $this->config = (new Config)->instance()->config;
  }

  public function __get($name){
      return new Application($name);
  }

  public function __call($name,$value){
    $file = __DIR__.'/../../../apps/'.$this->name.'/'.$name.'.php';
    if(is_file($file)) {
      $result = require($file);
      if(is_callable($result)) $result = call_user_func_array($result,$value);
      return $result;
    }
  }

}
?>
