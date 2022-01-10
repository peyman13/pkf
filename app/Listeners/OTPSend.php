<?php

namespace App\Listeners;

use App\Events\OTPSender;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\OTP;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OTPSend implements ShouldQueue
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
     * @param  OTPSender  $event
     * @return void
     */
    public function handle(OTPSender $event)
    {
        $otp = new OTP([]);
        $user = User::find($event->data->id);
        $user->password = $otp->getOTP($event->data->mobile);
        $user->password = Hash::make($user->password);
        $user->save();
    }
}
