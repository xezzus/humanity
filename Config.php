<?php
namespace humanity;

class Config {

    public static $config = [
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

    public function __construct($config=[]){
        self::$config = array_merge(self::$config,$config);
    }    

    public function get(){
        return self::$config;
    }

}
?>
