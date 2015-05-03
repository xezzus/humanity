<?php
namespace humanity;

class Console {

    public function __construct($config=[]){
        # Load config
        (new Config)->instance()->load($config);
    }

}
?>
