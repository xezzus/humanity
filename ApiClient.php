<?php
namespace humanity;

class ApiClient {

    private $url;
    private $appId;
    private $sig;

    public function __construct($url,$appId=null,$sig=null){
        $this->url = $url;
        $this->appId = $appId;
        $this->sig = $sig;
    }

    public function send($method,$params=[]){
        if(!is_array($params)) return false;
        $posts = [
            'method'=>$method,
            'params'=>$params
        ];
        if($this->appId !== null) {
            $posts['app_id'] = $this->appId;
        }
        if($this->sig !== null) {
            $posts['sig'] = $this->sig;
        }

        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_URL=>$this->url,
            CURLOPT_HEADER=>false,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_POST=>true,
            CURLOPT_VERBOSE=>false,
            CURLOPT_CONNECTTIMEOUT=>3,
            CURLOPT_TIMEOUT=>3,
            CURLOPT_USERAGENT=>"YotaLot 0.1",
        ]);
        curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($posts));
        $json = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($json,true);
        if(!is_array($json)) {
            return false;
        } else {
            return $json;
        }
    }

}
?>
