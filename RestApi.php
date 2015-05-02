<?php
namespace humanity;

class RestApi extends Application {

    public $post = [];

    public function __construct(){
        header('Content-Type: application/json');
        $this->post = $_POST;
        if(!preg_match('/^[a-z]{1,15}\.[a-z]{1,15}$/',$this->post['method'])) die('{}');
        $status = (new Api)->findStatusMethod($this->post['method']);
        if($status != 'PUBLIC') die('{}');
        $method = explode('.',$this->post['method']);
        $params = $this->post['params'];
        $func = call_user_func_array([$this->{$method[0]},$method[1]],$params);
        if(!$func) die('{}');
        $func = json_encode($func);
        die($func);
    }

}
?>
