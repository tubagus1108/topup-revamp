<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Api\AuthController as AuthGatewayController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MembersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/get-transaction', function () {
    Illuminate\Support\Facades\Artisan::call("app:gopay-run");
});
Route::group(['prefix' => 'admin'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    });
    Route::middleware('check.login')->group(function () {
        // Definisikan rute-rute yang memerlukan akses login di sini
        Route::get('dashboard', [DashboardController::class, 'index']);

        Route::group(['prefix' => 'members'], function () {
            Route::get('', [MembersController::class, 'index'])->name('members');
            Route::post('add', [MembersController::class, 'store']);
            Route::get('datatable', [MembersController::class, 'datatableMembers'])->name('datatable.members');
        });
    });
});
