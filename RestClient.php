<?php
namespace humanity;

class RestClient {

    public $url;
    public $headers = [];
    public $cookie;

    public function __construct($url){
        $this->url = $url;
    }

    public function headers($array){
        foreach($array as $key=>$value){ $this->headers[] = $key.': '.$value; }
        return $this;
    }

    public function cookie($cookie){
        if(is_array($cookie)) {
            foreach($cookie as $key=>$value){
                $cookie[$key] = $key.'='.$value;
            }
            $cookie = implode(';',$cookie);
        }
        $this->cookie = $cookie;
        return $this;
    }

    public function post($params){
        if(!is_array($params)) return false;
        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_HTTPHEADER=>$this->headers,
            CURLOPT_AUTOREFERER=>true,
            CURLOPT_FOLLOWLOCATION=>true,
            CURLOPT_COOKIESESSION=>true,
            CURLOPT_COOKIE=>$this->cookie,
            CURLOPT_COOKIEJAR=>'cookie.txt',
            CURLOPT_COOKIEFILE=>'cookie.txt',
            CURLOPT_URL=>$this->url,
            CURLOPT_HEADER=>false,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_POST=>true,
            CURLOPT_VERBOSE=>false,
            CURLOPT_CONNECTTIMEOUT=>3,
            CURLOPT_TIMEOUT=>3,
            CURLOPT_USERAGENT=>"PHP Framework Humanity",
        ]);
        curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($params));
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

    public function get($params=[]){
        if(!is_array($params)) return false;
        $curl = curl_init($this->url);
        curl_setopt_array($curl,[
            CURLOPT_HTTPHEADER=>$this->headers,
            CURLOPT_HEADER=>false,
            CURLOPT_AUTOREFERER=>true,
            CURLOPT_FOLLOWLOCATION=>true,
            CURLOPT_COOKIESESSION=>true,
            CURLOPT_COOKIE=>$this->cookie,
            CURLOPT_COOKIEJAR=>'cookie.txt',
            CURLOPT_COOKIEFILE=>'cookie.txt',
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_VERBOSE=>false,
            CURLOPT_CONNECTTIMEOUT=>3,
            CURLOPT_TIMEOUT=>3,
            CURLOPT_USERAGENT=>"PHP Framework Humanity",
        ]);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

}
?>
