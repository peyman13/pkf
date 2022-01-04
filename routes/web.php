<?php

use Illuminate\Support\Facades\Route;
use App\Services\IBAN;
use App\Services\Prkar as PrkarService;


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

    $pg_key = "34eaafa27f89c94b9901f33c2b4c71bc78a0ee9e12f118604722785fb25404b3d02cf1123e1daa64d84972d06527166286e0b22579ef3add448c4cde1a2e2224";
    $pg_terminal = "71000002";
    $pg_merchant = "213143400000002";
    $pg_order_id    = "234234";
    $pg_revert_url  = "http://cpors.icsdev.ir/test";
    $pg_customer_id = "3245645645256";

    $pg_transaction_amount = "100000";
    $pg_date               = date('Y/m/d');
    $pg_time               = date('H:i:s');
    $pg_sign               = $pg_merchant . '*' . $pg_terminal . '*' . $pg_order_id . '*' . $pg_revert_url . '*' . $pg_transaction_amount . '*' . $pg_date . '*' . $pg_time;
    
    $pg_sign_hash       = hash_hmac('sha256', $pg_sign, pack('H*', $pg_key));
    // merchant_id*terminal_id*order_id*revert_url*transaction_amount*split_amounts*date*time*identifier
    // "MERCHANT0000501*P0001501*234234*http://cpors.icsdev.ir/test*100000*2022/01/04*06:45:20"
    // dd($pg_sign_hash);

    $data = array(
        'merchant_id' => $pg_merchant,
        'terminal_id' => $pg_terminal,
        'order_id' => $pg_order_id,
        'revert_url' => $pg_revert_url,
        'customer_id' => $pg_customer_id,
        'transaction_amount' => $pg_transaction_amount,
        'date' => $pg_date,
        'time' => $pg_time,
        'metadata' => '',
        'sign' => $pg_sign_hash,
    );

    $ipg_url = "https://pec.shaparak.ir/NewPG/service/payment/transactionRequest";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ipg_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    // dd($result);
    curl_close ($ch);
    $transaction_id = explode(",",$result)[1];
    echo ($transaction_id)."<br />";
    
    $transactionSign = hash_hmac('sha256', $transaction_id , pack('H*', $pg_key));
    echo ($transactionSign)."<br />";

    echo '<form action="https://pec.shaparak.ir/NewPG/pay" method="POST">
        <input type="text" name="transaction_id" value="'.$transaction_id.'"/>
        <input type="text" name="sign" value="'.$transactionSign.'"/>
        <input type="submit" value="submit"/>
        </form>';
    // return view('welcome');
});

// AAA API
Route::post('/api/v1/login', [App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('/api/v1/otp', [App\Http\Controllers\API\AuthController::class, 'otp']);
Route::post('/api/v1/otp-refresh', [App\Http\Controllers\API\AuthController::class, 'otpRefresh']);

// Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');