<?php
namespace humanity;

class Site {

    public function __construct(){
        switch($_SERVER['HTTP_ACCEPT']){
            case "application/view":
                header('Access-Control-Allow-Origin: *');
                header('Access-Control-Max-Age: 31556926'); 
                header('Access-Control-Allow-Credentials: true'); 
                header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
                header('Content-Type: text/html');
                (new RestApi)->view();
            break;
            case "application/widget":
                header('Access-Control-Allow-Origin: *');
                header('Access-Control-Max-Age: 31556926'); 
                header('Access-Control-Allow-Credentials: true'); 
                header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
                header('Content-Type: text/html');
                (new RestApi)->widget();
            break;
            case "application/apps":
                header('Access-Control-Allow-Origin: *');
                header('Access-Control-Max-Age: 31556926'); 
                header('Access-Control-Allow-Credentials: true'); 
                header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
                header('Content-Type: application/json');
                (new RestApi)->apps();
            break;
            default:
                (new Content)->page();
        }
    }

}
?>
