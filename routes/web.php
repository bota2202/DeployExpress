<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductionOrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'form'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/rastreio', [DeliveryController::class, 'track'])->name('track');

Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('/entregas', [DeliveryController::class, 'index'])->name('deliveries.index');
    Route::get('/entregas/nova', [DeliveryController::class, 'create'])->middleware('role:expedicao,gerencia')->name('deliveries.create');
    Route::post('/entregas', [DeliveryController::class, 'store'])->middleware('role:expedicao,gerencia')->name('deliveries.store');
    Route::get('/entregas/{delivery}', [DeliveryController::class, 'show'])->name('deliveries.show');
    Route::post('/entregas/{delivery}/eventos', [DeliveryController::class, 'addEvent'])->middleware('role:expedicao,gerencia')->name('deliveries.event');

    Route::middleware('role:fabrica,expedicao,gerencia')->group(function () {
        Route::get('/produtos', [ProductController::class, 'index'])->name('products.index');
        Route::get('/ordens', [ProductionOrderController::class, 'index'])->name('orders.index');
    });

    Route::middleware('role:fabrica,gerencia')->group(function () {
        Route::get('/produtos/novo', [ProductController::class, 'create'])->name('products.create');
        Route::post('/produtos', [ProductController::class, 'store'])->name('products.store');
        Route::get('/produtos/{product}/editar', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/produtos/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::get('/ordens/nova', [ProductionOrderController::class, 'create'])->name('orders.create');
        Route::post('/ordens', [ProductionOrderController::class, 'store'])->name('orders.store');
        Route::post('/ordens/{order}/pronta', [ProductionOrderController::class, 'ready'])->name('orders.ready');
    });

    Route::post('/ordens/{order}/receber', [ProductionOrderController::class, 'receive'])->middleware('role:expedicao,gerencia')->name('orders.receive');

    Route::middleware('role:gerencia')->group(function () {
        Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
        Route::get('/usuarios/novo', [UserController::class, 'create'])->name('users.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');
    });
});
