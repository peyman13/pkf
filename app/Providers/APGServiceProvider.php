<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\APG;


class APGServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\SMSServiceInterface', function ($app) {
            return new APG($app['config']->get('apg'));
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
            return new APG($app['config']->get('apg'));
        });
    }
}
