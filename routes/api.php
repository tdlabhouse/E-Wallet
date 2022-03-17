<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LambungKapalController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// auth
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
});

// master
Route::group([
    'middleware' => 'api',
    'prefix' => 'master'
], function ($router) {
    Route::post('/add-rekening', [RekeningController::class, 'store']);
    Route::post('/edit-rekening', [RekeningController::class, 'edit']);
    Route::get('/get-rekening', [RekeningController::class, 'index']);
    Route::post('/delete-rekening', [RekeningController::class, 'destroy']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'transaksi'
], function ($router) {
    Route::post('/topup', [TransaksiController::class, 'topup']);
    Route::post('/withdraw', [TransaksiController::class, 'withdraw']);
    Route::post('/transfer', [TransaksiController::class, 'transfer']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'report'
], function ($router) {
    Route::get('/mutasi', [TransaksiController::class, 'report']);
});

Route::get('/lambung-kapal', [LambungKapalController::class, 'lambungKapal']);
