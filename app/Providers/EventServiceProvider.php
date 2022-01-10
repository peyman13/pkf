<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use App\Events\OTPSender;
use App\Events\IPGPersianEvent;
use App\Events\IPGPersianConfirmEvent;
use App\Listeners\OTPSend;
use App\Listeners\IPGPersianListener;
use App\Listeners\IPGPersianConfirmListener;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OTPSender::class => [
            OTPSend::class,
        ],
        IPGPersianEvent::class => [
            IPGPersianListener::class,
        ],        
        IPGPersianConfirmEvent::class => [
            IPGPersianConfirmListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
