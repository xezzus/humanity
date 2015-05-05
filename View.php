<?php
namespace humanity;

class View {

    public function __get($name){
        $content = new Content((new Config)->instance()->config['paths']['view'].'/'.$name.'/');
        $content->load();
        echo $content->getContent();
    }

}
?>
