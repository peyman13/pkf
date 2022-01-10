<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $fillable = [
        'transaction_id',
        'service_id',
        'merchant_id',
        'terminal_id',
        'order_id',
        'revert_url',
        'customer_id',
        'transaction_amount',
        'date',
        'time',
        'status',
        'created_at',
        'updated_at',
    ];
}
