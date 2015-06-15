# Humanity PHP Framework

## Application

```php
$this->app-nameMethod();
or
$this->app->namespace->nameMethod()

file ../apps/nameMethod.php

return function(){
    return true;
}

or 

file ../apps/namespace/nameMethod.php

return function(){
    return true;
}
```

## Singleton

```php
return function(){
    if(!isset($this->singleton->namesingle)){
        $this->singleton->namesingle = microtime(1)
    }
    return $this->singleton->namesingle
}
```

## WsDaemon

example

```php
set_time_limit(0);
ob_implicit_flush();

$ws = new WsDaemon('127.0.0.1',8888);

$ws->run(function($data,$clientId){
    var_dump($clientId);
    var_dump($data);
    return ['clients'=>$clientId,'msg'=>$data];
});
```
