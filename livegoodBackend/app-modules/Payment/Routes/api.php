<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\WithdrawalController;
use Modules\Payment\Http\Controllers\PaymentController;

/*
| API Routes for Payment Module
*/

Route::middleware('auth:api')->prefix('payments')->group(function () {
    
    // Retraits (Withdrawals)
    Route::get('/withdrawals', [WithdrawalController::class, 'index']);
    Route::post('/withdrawals', [WithdrawalController::class, 'store']);

    // Historique des transactions
    Route::get('/history', [PaymentController::class, 'history']);
});
