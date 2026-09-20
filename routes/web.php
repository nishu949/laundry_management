<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RateCardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/orders',                  [OrderController::class, 'index'])         ->name('orders.index');
Route::get('/orders/create',           [OrderController::class, 'create'])        ->name('orders.create');
Route::post('/orders',                 [OrderController::class, 'store'])         ->name('orders.store');
Route::get('/orders/{order}',          [OrderController::class, 'show'])          ->name('orders.show');
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])  ->name('orders.update-status');
Route::patch('/orders/{order}/payment',[OrderController::class, 'updatePayment']) ->name('orders.update-payment');

Route::resource('rate-cards', RateCardController::class)
    ->except(['show'])
    ->parameters(['rate-cards' => 'rateCard']);