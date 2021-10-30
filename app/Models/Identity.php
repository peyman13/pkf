<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    // protected static function booted()
    // {
    //     static::created(function ($Identity) {
    //         echo json_encode($Identity)."\n";
    //     });
    // }
}
