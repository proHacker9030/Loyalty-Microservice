<?php

use App\Http\Controllers\Admin\OrderOperationsController;
use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () {
 // custom admin routes
    Route::crud('project', 'ProjectCrudController');
    Route::get('health', [HealthCheckResultsController::class, '__invoke']);
    Route::crud('order', 'OrderCrudController');
    Route::prefix('/orders-operation/{id}/')->group(function () {
        Route::post('cancel-force', [OrderOperationsController::class, 'forceCancelOrder'])->name('admin-cancel_force-order');
        Route::post('cancel', [OrderOperationsController::class, 'cancelOrder'])->name('admin-cancel-order');
        Route::post('confirm', [OrderOperationsController::class, 'confirmOrder'])->name('admin-confirm-order');
        Route::post('set-fiscal-check', [OrderOperationsController::class, 'setFiscalCheck'])->name('admin-set_fiscal_check-order');
    });
}); // this should be the absolute last line of this file
