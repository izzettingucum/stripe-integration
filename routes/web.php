<?php

declare(strict_types=1);

use App\Http\Controllers\ProductController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'displayProductListPage'])->name('display.stripe.charge.page');
Route::post('/charge', [StripeController::class, 'chargePayment'])->name('stripe.charge');
Route::get('/charge/success', [StripeController::class, 'displaySuccessPage'])->name('stripe.charge.success');
