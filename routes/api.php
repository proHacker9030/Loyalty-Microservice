<?php

use App\Http\Controllers\Api\BonusesController;
use App\Http\Controllers\Api\InfoController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\PayController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\RefundController;
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

Route::middleware('auth')->group(function () {

    Route::prefix('info')->group(function () {
        Route::get('/user-identifier-type', [InfoController::class, 'getUserIdentifierType']);
    });

    Route::prefix('bonuses')->group(function () {
        Route::get('/available', [BonusesController::class, 'getAvailableBonuses']);
        Route::get('/order-amount-bonuses', [BonusesController::class, 'getOrderAmountAndBonuses']);
        Route::post('/spend', [BonusesController::class, 'spendBonuses']);
        Route::post('/re-spend', [BonusesController::class, 'reSpendBonuses']);
    });

    Route::prefix('promocode')->group(function () {
        Route::post('/apply', [PromoController::class, 'applyCode']);
        Route::post('/cancel', [PromoController::class, 'cancelCode']);
    });

    Route::prefix('pay')->group(function () {
        Route::post('/set-fiscal-check', [PayController::class, 'setFiscalCheck']);
        Route::post('/cancel-fiscal-check', [PayController::class, 'cancelFiscalCheck']);
        Route::post('/confirm-order', [PayController::class, 'confirmOrder']);
    });

    Route::prefix('refund')->group(function () {
        Route::post('/order', [RefundController::class, 'refundOrder']);
        Route::post('/cart', [RefundController::class, 'refundCart']);
    });

    Route::post('/disable', [MainController::class, 'disableLoyalty']);
});
