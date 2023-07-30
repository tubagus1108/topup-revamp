<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RestApiController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
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
        Route::get('profile',[RestApiController::class,'profile']);
    });
});

Route::group(['prefix' => 'auth'],function(){
    Route::post('login',[AuthController::class,'login']);
});

Route::group(['middleware' => ['jwt', 'role']], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('datatable', [UserController::class,'getUsersDatatable']);
        Route::post('create',[UserController::class,'createUser']);
        Route::get('{id}/detail', [UserController::class, 'detailUser']);
        Route::delete('{id}/delete', [UserController::class, 'deleteUser']);
        Route::put('{id}/update', [UserController::class, 'editUser']);
    });
});
