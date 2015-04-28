# Humanity PHP Framework

## Application

```
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

```
return function(){
    if(!isset($this->singleton->namesingle)){
        $this->singleton->namesingle = microtime(1)
    }
    return $this->singleton->namesingle
}
```

## Validating

```
...
```
