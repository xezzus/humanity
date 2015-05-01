<?php
namespace humanity;

class RestApi extends Application {

    public $post = [];

    public function __construct(){
        $this->post = $_POST;
        $method = explode('.',$this->post['method']);
        $params = $this->post['params'];
        $func = call_user_func_array([$this->{$method[0]},$method[1]],$params);
        $func = json_encode($func);
        header('Content-Type: application/json');
        die($func);
    }

}
?>
