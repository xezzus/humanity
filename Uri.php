<?php
namespace humanity;

class Uri {

    private $requestUri = '';

    public function __construct(){
        $this->requestUri = $_SERVER['REQUEST_URI'];
        $tmp = explode('/',$this->requiestUri);
        foreach($tmp as $key=>$value){
            $value = trim($value);
            if(empty($value)) unset($tmp[$key]);
            else $tmp[$key] = $value;
        }
        $tmp = array_values($tmp);
        $this->requestUri = $tmp;
    }

    public function full(){
        return '/'.implode('/',$this->requestUri);
    }

    public function part($number){
        if(isset($this->requestUri[$number])) return (string) urldecode($this->requestUri[$number]);
        else return '';
    }

    public function host(){
        return parse_url('http://'.$_SERVER['HTTP_HOST'])['host']
    }

}
?>
