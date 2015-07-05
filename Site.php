<?php
namespace humanity;

class Site {

    public function __construct(){
        switch($_SERVER['HTTP_ACCEPT']){
            case "application/view":
                (new RestApi)->view();
            break;
            case "application/widget":
                (new RestApi)->widget();
            break;
            case "application/apps":
                (new RestApi)->apps();
            break;
            default:
                (new Content)->page();
        }
    }

}
?>
