<?php
namespace humanity;

class View {

    public function __get($name){
       (new Content)->view($name); 
    }

}
?>
