<?php

use Illuminate\Support\Facades\Route;
use App\Services\IBAN;


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
    $otp = new IBAN([]);
    dd($otp->isValidIBAN("IR540120000000004777631304", "0010886941", "19999999"));
    return view('welcome');
});

// AAA API
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('/otp', [App\Http\Controllers\API\AuthController::class, 'otp']);
Route::post('/otp-refresh', [App\Http\Controllers\API\AuthController::class, 'otpRefresh']);

// Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');