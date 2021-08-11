<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SMS;

class SMSServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Library\Services\Contracts\CustomServiceInterface', function ($app) {
            return new SMS($app['config']->get('sms'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('App\Services\SMS', function ($app) {
        
            return new SMS($app['config']->get('sms'));
        });
    }
}
