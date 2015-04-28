<?php
namespace humanity;

class View {

  public function __get($name){
    echo (new Content(__DIR__.'/../../../web/view/'.$name.'/'))->load();
  }

}
?>
