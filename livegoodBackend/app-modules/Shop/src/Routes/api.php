<?php

use Illuminate\Support\Facades\Route;
use Modules\Shop\Http\Controllers\ShopController;

/*
| API Routes for Shop Module
*/

Route::middleware('auth:api')->prefix('shop')->group(function () {
    Route::get('/products', [ShopController::class, 'index']);
    Route::post('/cart', [ShopController::class, 'addToCart']);
    Route::post('/checkout', [ShopController::class, 'checkout']);
});
