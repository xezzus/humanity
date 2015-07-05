<?php
namespace humanity;

class Config {

    private static $instance = null;
    public $config = [
        'paths'=>[
            'page'=>__DIR__.'/../../../web/page',
            'view'=>__DIR__.'/../../../web/view',
            'widget'=>__DIR__.'/../../../web/widget',
            'apps'=>__DIR__.'/../../../apps',
            'js'=>__DIR__.'/../../../web/js',
            'css'=>__DIR__.'/../../../web/css'
        ],
        'api'=>[
            'file'=>__DIR__.'/../../../api.db'
        ]
    ];

    private function __clone(){
        
    }

    public function instance(){
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function load($config){
        $this->config = array_merge($this->config,$config);
    }

}
?>
