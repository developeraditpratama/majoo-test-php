<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('transaction/', [TransactionController::class, 'index']);
    Route::get('transaction/laporan-all-merchant', [TransactionController::class, 'laporanAllMerchant']);
    Route::get('transaction/laporan-all-outlet', [TransactionController::class, 'laporanAllOutlet']);
});
