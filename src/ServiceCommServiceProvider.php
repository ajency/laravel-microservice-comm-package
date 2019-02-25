<?php

namespace Ajency\ServiceComm;

use Illuminate\Support\ServiceProvider;

class ServiceCommServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
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