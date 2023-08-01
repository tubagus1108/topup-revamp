<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CallbackController;
use App\Http\Controllers\Api\RestApiController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Requests\OrderPrepaidRequest;
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

//Documention User Order by Rest Api
Route::group(['middleware' => ['verifiedToken','verifiedIP','verifiedRole']],function(){
    Route::group(['prefix' => 'v1'],function(){
        Route::post('profile',[RestApiController::class,'profile']);
        Route::post('product',[RestApiController::class,'product']);
        Route::post('status',[RestApiController::class,'status']);

        //ORDER PREPAID
        Route::group(['prefix' => 'order'], function(){
            Route::post('prepaid',[RestApiController::class,'order_prepaid']);
        });

    });
});
Route::post('callback',[CallbackController::class,'callback']);

Route::group(['prefix' => 'auth'],function(){
    Route::post('login',[AuthController::class,'login']);
    Route::post('register',[AuthController::class,'register']);
});

Route::group(['middleware' => ['jwt']], function () {
    Route::group(['middleware' => ['role']],function(){
        Route::group(['prefix' => 'users'], function () {
            Route::get('datatable', [UserController::class,'getUsersDatatable']);
            Route::post('create',[UserController::class,'createUser']);
            Route::get('{id}/detail', [UserController::class, 'detailUser']);
            Route::delete('{id}/delete', [UserController::class, 'deleteUser']);
            Route::put('{id}/update', [UserController::class, 'editUser']);
        });
    });

    Route::group(['prefix' => 'service'],function(){
        Route::get('datatable',[ServiceController::class,'getServiceDatatable']);
    });
});

