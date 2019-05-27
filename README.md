# laravel-microservice-comm-package
## Sync API calls to Microservices
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
## Async calls to services like AWS SNS

### Config changes
Add the Topics to the `service_comm.php` config file. If the topic is not added to the config, the package will throw an error while processing that topic.
> 'topics' => ['OrderCreated', 'OrderUpdated','SignUp']

### Create a topic
To create a topic, create an instance of the SNS class with the static method `createInstance`
```php
use Ajency\ServiceComm\Comm\SNS;
$sns = SNS::createInstance();
$sns->createTopic('OrderCreated')
```

### Publish to a topic
To publish to a topic call static method `call` from `Async` Class, this returns a promise which is resolved to an `AWSResult` object
```php
use Ajency\ServiceComm\Comm\Async;
$promise = Async::call('OrderCreated',['id'=>2, 'name'=> 'ABC']);
$result = $promise->wait();
```
We can also publish to AWS Simple Notification Service without using Promises
```php
use Ajency\ServiceComm\Comm\Async;
$result = Async::call('OrderCreated',['id'=>2, 'name'=> 'ABC'],'sns',false);
```
## Read a SNS notification from the subscribed queue in SQS
This package implements the [AWS SQS SNS Subscription Queue](https://github.com/joblocal/laravel-sqs-sns-subscription-queue) package. Please refer the readme [here](https://github.com/joblocal/laravel-sqs-sns-subscription-queue/blob/master/readme.md)
