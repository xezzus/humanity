<?php
namespace humanity;

class Application {

  public $result;
  private $name;

  public function __construct($name=''){
      $this->name = $name;
  }

  public function __get($name){
      return new Application($name);
  }

  public function __call($name,$value){
    $file = __DIR__.'/../../../app/'.$this->name.'/'.$name.'.php';
    var_dump($file);
    if(is_file($file)) {
      $result = require($file);
      if(is_callable($result)) $this->result = call_user_func_array($result,$value);
      return $this->result;
    }
  }

}
?>
