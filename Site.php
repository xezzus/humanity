<?php
namespace humanity;

class Site {

    private static $accept;
    private static $config;

    public function __construct(){

        self::$config = (new Config)->get();
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
            $query = json_decode(file_get_contents('php://input'),1);
            $result = [];
            foreach($query['method'] as $method=>$params){
                $stableParams = [];
                $file = self::$config['paths']['apps'].'/'.$method.'.php';
                $func = require_once($file);
                $reflection = new \ReflectionFunction($func);
                foreach($reflection->getParameters() as $key=>$value){
                    if(isset($params[$value->name])) $stableParams[$value->name] = $params[$value->name];
                    else $stableParams[$value->name] = null;
                }
                $name = explode('/',$method);
                $count = count($name)-1;
                $app = new Application;
                foreach($name as $key=>$var){
                    if($count == $key) $app = call_user_method_array($var,$app,$stableParams);
                    else $app = $app->{$var};
                }
                $result['method'][$method] = $app;
            }
            die(json_encode($result));
        } else {
            (new Content)->page();
        }
    }

}
?>
