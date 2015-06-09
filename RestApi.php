<?php
namespace humanity;

class RestApi extends Application {

    public $post = [];

    public function __construct(){
        # Header
        header('Content-Type: application/json');
        # Get post
        $this->post = (strstr($_SERVER['CONTENT_TYPE'],'application/json')) ? json_decode(file_get_contents('php://input'),1) : $_POST;
        # Check
        if(!isset($this->post['method'])) die('{}');
        # Filter
        if(!preg_match('/^[A-z]{1,15}\.[A-z]{1,15}$/',$this->post['method'])) die('{}');
        # Hack
        $this->post['method'] = Html::encode(trim($this->post['method']));
        if(isset($this->post['app_id'])) $this->post['app_id'] = (int) trim($this->post['app_id']);
        if(isset($this->post['sig'])) $this->post['sig'] = Html::encode(trim($this->post['sig']));

        $apiDb = new Api;
        # Find method
        $method = $apiDb->findMethod($this->post['method']);
        if($method['status'] == 'PRIVATE'){
            $auth = false;
            if(isset($this->post['app_id']) && isset($this->post['sig'])){
                $auth = $apiDb->authApp((int) $this->post['app_id'],$this->post['sig']);
                if($auth === true){
                    $permission = $apiDb->isPermission($this->post['app_id'],$this->post['method']);
                    if($permission === false) $auth = false;
                }
            }
            if($auth === false) die('{"msg":"Failed","info":"No authentication"}');
        } else {
            if($method['status'] != 'PUBLIC') die('{"msg":"Failed","info":"This method deny"}');
        }

        # Filter params
        $method['params'] = json_decode($method['params']);
        $validator = [];
        $required = [];
        $params = [];
        if(!empty($method['params']) && is_object($method['params'])){
            foreach($method['params'] as $name => $value){
                if(isset($this->post['params'][$name])){
                    $params[$name] = $this->post['params'][$name];
                    if(is_string($params[$name])) $params[$name] = trim($params[$name]);
                } 
                if(!isset($params[$name]) || empty($params[$name])) $params[$name] = null;
                if($value->require == 'true' && is_null($params[$name])) $required[] = $name;
                if(!is_null($params[$name])) {
                    if((new Verify($params[$name]))->{$value->validator} === false) {
                        $validator[] = $name;
                    } else {
                        if(is_array($params[$name])){
                            $params[$name] = array_map(function($value){
                                return Html::encode(trim($value));
                            },$params[$name]);
                        } else {
                            $params[$name] = Html::encode($params[$name]);
                        }
                    }
                }
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
        if(is_array($func)) $func = json_encode($func);
        else $func = '{}';
        die($func);
    }

}
?>
