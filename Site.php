<?php
namespace humanity;

class Site {

    private static $accept;

    public function __construct(){

        self::$accept = (new Accept)->instance();

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Max-Age: 31556926'); 
        header('Access-Control-Allow-Credentials: true'); 
        header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
        if(isset(self::$accept->application['view'])){
            header('Content-Type: text/html');
            (new RestApi)->view();
        } else if(isset(self::$accept->application['widget'])){
            header('Content-Type: text/html');
            (new RestApi)->widget();
        } else if(isset(self::$accept->application['apps'])){
            header('Content-Type: application/json');
            (new RestApi)->apps();
        } else {
            (new Content)->page();
        }
    }

}
?>
