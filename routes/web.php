<?php

use Illuminate\Support\Facades\Route;
use App\Services\OTP;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $otp = new OTP([]);
    dd($otp->getOTP());

    return view('welcome');
});

Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('/otp', [App\Http\Controllers\API\AuthController::class, 'otp']);


Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
