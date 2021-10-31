<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\OTPSender;


class Identity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mobile',
        'identity',
    ];

    protected static function booted()
    {
        OTPSender::dispatch();
        // static::created(function ($Identity) {
        //     OTPSender::dispatch($Identity);
        // });
    }
}
