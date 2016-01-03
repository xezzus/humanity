<?php
namespace humanity;

class Site {

    private static $js;

    public function __construct(){

        $config = (new Config)->get();

        if(!isset($_SERVER['HTTP_ACCEPT'])) $_SERVER['HTTP_ACCEPT'] = '*/*';
        $acceptSrc = explode(',',$_SERVER['HTTP_ACCEPT']);
        foreach($acceptSrc as $key=>$value){
            $value = explode('/',$value);
            $value[1] = explode(';',$value[1]);
            $name = array_shift($value[1]);
            $data = [];
            foreach($value[1] as $k=>$v){
                $v = explode('=',$v);
                $data[$v[0]] = $v[1];
            }
            $accept[$value[0]][$name] = $data;
        }

        unset($acceptSrc,$key,$value,$name,$data,$k,$v);

        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        if($requestUri['path'] == '/javascript.js'){
            header('Content-Type: application/javascript');

            $tsstring = 'Sun, 03 Jan 2016 11:22:20 GMT';
            $etag = md5($tsstring);
            $if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false;
            $if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : false;
            if ((($if_none_match && $if_none_match == $etag) || (!$if_none_match)) &&
                ($if_modified_since && $if_modified_since == $tsstring))
            {
                header('HTTP/1.1 304 Not Modified');
                exit();
            }
            else
            {
                header("Last-Modified: $tsstring");
                header("ETag: $etag");
            }
            
            if(isset($requestUri['query'])){
                $query = explode(':',$requestUri['query']);
                (new Content)->js($query);
            }
            exit;
        }

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Max-Age: 31556926'); 
        header('Access-Control-Allow-Credentials: true'); 
        header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');

        if(isset($accept['application']['view'])){
            header('Content-Type: text/html');
            (new RestApi)->view();
        } else if(isset($accept['application']['widget'])){
            header('Content-Type: text/html');
            (new RestApi)->widget();
        } else if(isset($accept['application']['apps'])){
            header('Content-Type: application/json');
            $query = json_decode(file_get_contents('php://input'),1);
            $result = [];
            foreach($query as $method=>$params){
                $stableParams = [];
                $file = $config['core']['apps'].'/'.$method.'.php';
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
                $result[$method] = $app;
            }
            die(json_encode($result));
        } else {
            (new Content)->page();
        }
    }

}
?>
