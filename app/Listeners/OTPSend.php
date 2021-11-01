<?php

namespace App\Listeners;

use App\Events\OTPSender;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\OTP;


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
        // $otp = new OTP([]);
        // dd($otp->getOTP());
        // echo json_encode($event)."\n";
        // // json_encode($event);die;
    }
}
