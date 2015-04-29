<?php
namespace humanity;

class View {

    public function __get($name){
        $content = new Content(__DIR__.'/../../../web/view/'.$name.'/');
        $content->load();
        echo $content->getContent();
    }

}
?>
