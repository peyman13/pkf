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
    $requestContent = [
        "employee" => [
            "education" => "BCH",
            "maritalStatus" => "SNG",
            "militaryStatus" => "EXCL",
            "religion" => null,
            "bodyState" => "HLT",
            "sect" => null,
            "numberOfChildren" => 0
        ],
        "person" => [
            "firstName" => "هادي",
            "lastName" => "قهرمان زاده باروق",
            "fatherName" => "كرم",
            "birthDate" => "1373/04/25",
            "sex" => "MAN",
            "nationalId" => "0010886941",
            "identityId" => "0",
            "iban" => "14508648641450864864",
            "personImage" => null,
            "nationality" => "iranian",
            "homeContact" => [
                "postalCode" => "1431814519",
                "phoneNumberCode" => "021",
                "phoneNumber" => "66179376",
                "mobileNumber" => "9124655785",
                "province" => "23",
                "city" => "2301",
                "address" => "زنجان جنوبی، دامپزشکی نرسیده به یادگار  کوچه محسن بن بست بهمن پ 2 واحد 2",
                "email" => "hadi.gb73@gmail.com"
            ]
        ],
        "starter" => "online",
        "iban" => "IR550590014681302536847001",
        "ekycVideoReference" => "IR550590014681302536847001"
    ];
    $prkarService = new PrkarService();
    dd($prkarService->setEmployee($requestContent));
    // $otp = new IBAN([]);
    // dd($otp->isValidIBAN("IR540120000000004777631304", "0010886941", "19999999"));
    return view('welcome');
});

// AAA API
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('/otp', [App\Http\Controllers\API\AuthController::class, 'otp']);
Route::post('/otp-refresh', [App\Http\Controllers\API\AuthController::class, 'otpRefresh']);

// Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');