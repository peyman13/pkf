<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\IBAN;

class IBANServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\IBANServiceInterface', function ($app) {
            return new IBAN($app['config']->get('pkar'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('App\Services\IBAN', function ($app) {
            return new IBAN($app['config']->get('pkar'));
        });
    }
}
