<?php

use Illuminate\Support\Facades\Route;
use App\Services\IBAN;
use App\Services\Prkar as PrkarService;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Http;
use App\Events\IPGPersianEvent;


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

    // dd(event(new IPGPersianEvent(['wefwef']));
    //string $orderId, string $customer, string $price
    $data = [
        "orderid" => "23452345",
        "customer" => "234",
        "price" => "100000"
    ];
    $params = IPGPersianEvent::dispatch($data)[0];
    echo '<form action="https://pec.shaparak.ir/NewPG/pay" method="POST">
        <input type="text" name="transaction_id" value="'.$params['transactionId'].'"/>
        <input type="text" name="sign" value="'.$params['transactionSign'].'"/>
        <input type="submit" value="submit"/>
        </form>';

});

Route::post('/api/v1/pay', function (Request $request) {
    $data = [
        "orderid" => $request->get("orderid"),
        "customer" => $request->get("customer"),
        "price" => $request->get("price")
    ];
    $params = IPGPersianEvent::dispatch($data)[0];
    return response()->json($params);
});

Route::post('/api/v1/pay-revert', function (Request $request) {
    $data = [
        "orderid" => $request->get("orderid"),
        "customer" => $request->get("customer"),
        "price" => $request->get("price")
    ];
    $params = IPGPersianEvent::dispatch($data)[0];
    return response()->json($params);
});

// AAA API
Route::post('/api/v1/login', [App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('/api/v1/otp', [App\Http\Controllers\API\AuthController::class, 'otp']);
Route::post('/api/v1/otp-refresh', [App\Http\Controllers\API\AuthController::class, 'otpRefresh']);

// Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
