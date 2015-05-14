<?php
namespace humanity;

class RestApi extends Application {

    public $post = [];

    public function __construct(){
        # Header
        header('Content-Type: application/json');

        # Get post
        $this->post = $_POST;
        if(!preg_match('/^[A-z]{1,15}\.[A-z]{1,15}$/',$this->post['method'])) die('{}');

        # Find method
        $method = (new Api)->findMethod($this->post['method']);
        if($method['status'] != 'PUBLIC') die('{"msg"=>"Failed"}');

        # Filter params
        $method['params'] = json_decode($method['params']);
        $validator = [];
        $required = [];
        $params = [];
        if(!empty($method['params']) && is_object($method['params'])){
            foreach($method['params'] as $name => $value){
                if(!isset($this->post['params'][$name])) { $params[$name] = null; }
                else $params[$name] = trim(Html::encode($this->post['params'][$name]));
                if($value->require == 'true' && empty($params[$name])) $required[] = $name;
                else if(empty($params[$name])) $params[$name] = null;
                if(!is_null($params[$name])) if((new Validators($params[$name]))->{$value->validator}() === false) $validator[] = $name;
            }
        }

        # Required
        if(!empty($required)) die(json_encode(['msg'=>'Failed','required'=>$required]));

        # Validator
        if(!empty($validator)) die(json_encode(['msg'=>'Failed','validator'=>$validator]));

        # Call method
        $call = explode('.',$this->post['method']);
        ksort($params,SORT_STRING);
        reset($params);
        $func = call_user_func_array([$this->{$call[0]},$call[1]],$params);
        if(!$func) die('{"msg":"Failed"}');
        $func = json_encode($func);
        die($func);
    }

}
?>
