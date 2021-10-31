<?php

namespace App\Listeners;

use App\Events\OTPSender;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OTPSend
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OTPSender  $event
     * @return void
     */
    public function handle(OTPSender $event)
    {
        // echo json_encode($event)."\n";
        // // json_encode($event);die;
    }
}
