<?php

namespace App\Listeners;

use App\Events\IPGPersianEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class IPGPersianListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  IPGPersianEvent  $event
     * @return void
     */
    public function handle(IPGPersianEvent $event)
    {
        return $event->data;
    }
}
