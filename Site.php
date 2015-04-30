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
    $js = $content->getJs();
    $page = $content->getContent();
    $page = preg_replace('/<\/head>/',"$css</head>",$page);
    $page = preg_replace('/<\/html>/',"$js</html>",$page);

    echo $page;
  }

}
?>
