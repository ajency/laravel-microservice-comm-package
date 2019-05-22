<?php

namespace Ajency\ServiceComm;

use Illuminate\Support\ServiceProvider;
use Aws\Sns\SnsClient;
use Ajency\ServiceComm\Comm\SNS;

class ServiceCommServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(SnsClient::class, function ($app, $config){
            return new SnsClient(config('service_comm.sns.client'));
        });

        $this->app->singleton(SNS::class, function ($app, $config) {
            return new SNS($app[SnsClient::class]);
        });

        $this->publishes([
            __DIR__ . '/config/service_comm.php' => config_path('service_comm.php'),
        ], 'config');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}