<?php

use App\Infrastructure\Controllers\GetsWalletCryptocurrenciesController;
use App\Infrastructure\Controllers\PostOpenWalletController;
use App\Infrastructure\Controllers\PostBuyCoinController;
use App\Infrastructure\Controllers\PostSellCoinController;
use Illuminate\Support\Facades\Route;

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

Route::get('/wallet/{wallet_id}', GetsWalletCryptocurrenciesController::class);
Route::post('/wallet/open', PostOpenWalletController::class);
Route::post('/coin/buy', PostBuyCoinController::class);
Route::post('/coin/sell', PostSellCoinController::class);
