<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CallbackController;
use App\Http\Controllers\Api\RestApiController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Gateway\CheckIgnController;
use App\Http\Controllers\Gateway\GojekController;
use App\Http\Controllers\Gateway\OrderController;
use App\Http\Controllers\Gateway\OvoPayController;
use App\Http\Controllers\PaymentController;
use App\Http\Requests\OrderPrepaidRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//CHECK IGN NAME
Route::post('check-ign', [CheckIgnController::class, 'index'])->name('index');

//Documention User Order by Rest Api
Route::group(['middleware' => ['verifiedToken', 'verifiedIP', 'verifiedRole']], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::post('profile', [RestApiController::class, 'profile']);
        Route::post('product', [RestApiController::class, 'product']);
        Route::post('status', [RestApiController::class, 'status']);

        //ORDER PREPAID
        Route::group(['prefix' => 'order'], function () {
            Route::post('prepaid', [RestApiController::class, 'order_prepaid']);
        });
    });
});
Route::post('callback', [CallbackController::class, 'callback']);

// GATEWAY ROUTE
Route::group(['prefix' => 'gateway'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });
    Route::group(['middleware' => ['jwt']], function () {
        Route::group(['prefix' => 'service'], function () {
            Route::get('datatable', [ServiceController::class, 'getServiceDatatable']);
        });
        Route::post('order', [OrderController::class, 'order']);

        Route::group(['prefix' => 'service'], function () {
            Route::get('datatable', [ServiceController::class, 'getServiceDatatable']);
        });

        Route::post('deposit', [PaymentController::class, 'createdDeposit']);
        Route::get('/service/{type}', [ServiceController::class, 'serviceType']);
        Route::get('/order/{type}/{code}', [ServiceController::class, 'layananDetail']);

        Route::get('/deposit/status', [PaymentController::class, 'status']);
        Route::get('/deposit', [PaymentController::class, 'list']);

        //Role route Admin
        Route::group(['middleware' => ['role']], function () {
            Route::group(['prefix' => 'settings'], function () {
                Route::group(['prefix' => 'payment'], function () {
                    Route::get('/gopay',                                    [GojekController::class, 'create'])->name('gopay');
                    Route::post('/gopay',                                   [GojekController::class, 'store'])->name('gopay.post');
                    Route::get('/gopay/gojek-otp',                     [GojekController::class, 'GetOTP']);
                    Route::post('/gopay/gojek-validasi',                    [GojekController::class, 'VerifOTP']);


                    Route::get('/ovopay',                                    [OvoPayController::class, 'create'])->name('gopay');
                    Route::post('/ovopay',                                   [OvoPayController::class, 'store'])->name('gopay.post');
                    Route::get('/ovopay/ovopay-otp',                     [OvoPayController::class, 'GetOTP']);
                    Route::post('/ovopay/ovopay-validasi',                    [OvoPayController::class, 'VerifOTP']);
                    Route::post('/ovopay/validasi-pin',                        [OvoPayController::class, 'VerifPIN']);
                });
            });
            Route::group(['prefix' => 'payment'], function () {
                Route::post('create', [PaymentController::class, 'created']);
            });

            Route::group(['prefix' => 'users'], function () {
                Route::get('datatable', [UserController::class, 'getUsersDatatable']);
                Route::post('create', [UserController::class, 'createUser']);
                Route::get('{id}/detail', [UserController::class, 'detailUser']);
                Route::delete('{id}/delete', [UserController::class, 'deleteUser']);
                Route::put('{id}/update', [UserController::class, 'editUser']);
            });
        });
    });
});
