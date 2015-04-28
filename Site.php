<?php
namespace humanity;

class Site {

  public function __construct($config=[]){
    # Load page
    echo (new Content($config['pathPage']))->load();
  }

}
?>
