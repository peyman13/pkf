<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Prkar;

class PrkarServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\PrkarServiceInterface', function ($app) {
            return new Prkar($app['config']->get('pkar'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('App\Services\Prkar', function ($app) {
        
            return new Prkar($app['config']->get('pkar'));
        });
    }
}
