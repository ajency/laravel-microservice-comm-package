# laravel-microservice-comm-package

### Install packages
`Add the repository to composer.json & run "composer update"`
```
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/ajency/laravel-microservice-comm-package.git"
    }
],
"require": {
    "ajency/service_comm": "dev-master"
}
```

### Config File
`php artisan vendor:publish`
> Lumen does not support this command, for it you should copy the file src/resources/config/service_comm.php to config/service_comm.php of your project

`Add auth_token to config`
`Add url to config - replace service_name, service_url`
`Add mapping to config - replace api_name, model_name, function_name`

### Add route to web.php
`Laravel`
> Route::post('/service_comm/listen', '\Ajency\ServiceComm\ServiceCommController@serviceCommListen');
`Lumen`
> $router->post('/service_comm/listen', '\Ajency\ServiceComm\ServiceCommController@serviceCommListen');

`For Laravel Add following to VerifyCsrfToken.php Middleware`
```
private $openRoutes = ['service_comm/listen'];

protected $except = [
    'service_comm/listen'
];
```
