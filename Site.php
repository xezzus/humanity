<?php
namespace humanity;

class Site {

  public function __construct($config=[]){
    # Load page
    $content = new Content($config['pathPage']);
    echo $content->load();
    //$js = (new Js)->instance();
    var_dump($content->js->list);
    var_dump($content->css->list);
  }

}
?>
