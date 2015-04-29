<?php
namespace humanity;

class Site {

  public function __construct($config=[]){
    # Load config
    (new Config)->instance()->load($config);
    # Load page
    $content = new Content($config['paths']['page']);
    $content->load();
    $css = $content->getCss();
    $page = $content->getContent();
    $page = preg_replace('/<\/head>/',"$css<\/head>",$page);

    echo $page;
  }

}
?>
