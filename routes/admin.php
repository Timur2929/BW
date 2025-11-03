<?php

use App\Http\Controllers\Admin\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/statistics', [OrderController::class, 'statistics'])->name('statistics');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::put('/{order}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
        Route::put('/{order}', [OrderController::class, 'update'])->name('update');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');
    });
});