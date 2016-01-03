<?php
namespace humanity;

class MinifyHTML {

    private $source;

    public function __construct($source){
        $this->source = $source;
    }

    public function compress(){
        $minify = $this->source;
        $minify = preg_replace('/>[\s]*</Ui','><',$minify);
        return $minify;
    }

}
?>
