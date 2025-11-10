<?php

use App\Http\Controllers\Admin\RecipeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
// Маршруты для управления заказами пользователей в системе
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
// Маршруты для управления пользователями системы
     Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
// Маршруты для управления категориями в системе
    Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::get('/create', [CategoryController::class, 'create'])->name('create');
    Route::post('/', [CategoryController::class, 'store'])->name('store');
    Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
});

// Маршруты для управления украшениями в системе
// Маршруты для управления украшениями
    Route::prefix('recipes')->name('recipes.')->group(function () {
        Route::get('/', [RecipeController::class, 'index'])->name('index');
        Route::get('/create', [RecipeController::class, 'create'])->name('create');
        Route::post('/', [RecipeController::class, 'store'])->name('store');
        Route::get('/{recipe}', [RecipeController::class, 'show'])->name('show');
        Route::get('/{recipe}/edit', [RecipeController::class, 'edit'])->name('edit');
        Route::put('/{recipe}', [RecipeController::class, 'update'])->name('update');
        Route::delete('/{recipe}', [RecipeController::class, 'destroy'])->name('destroy');
        Route::get('/pending', [RecipeController::class, 'pending'])->name('pending');
        Route::put('/{recipe}/status', [RecipeController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/{recipe}/review', [RecipeController::class, 'review'])->name('review');
        Route::post('/{recipe}/approve', [RecipeController::class, 'approve'])->name('approve');
        Route::post('/{recipe}/reject', [RecipeController::class, 'reject'])->name('reject');
        Route::post('/{recipe}/send-to-revision', [RecipeController::class, 'sendToRevision'])->name('sendToRevision');
    });



});