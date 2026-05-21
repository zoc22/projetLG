<?php

use Illuminate\Support\Facades\Route;
use Modules\Affiliation\Http\Controllers\AffiliateController;
use Modules\Affiliation\Http\Controllers\SubscriptionController;
use Modules\Affiliation\Http\Controllers\RankController;

/*
| API Routes for Affiliation Module
*/

Route::middleware('auth:sanctum')->prefix('affiliation')->group(function () {

    // Dashboard & Profile
    Route::get('/dashboard', [AffiliateController::class, 'dashboard']);
    Route::get('/me', [AffiliateController::class, 'me']);

    // Gestion Abonnement
    Route::get('/subscription', [SubscriptionController::class, 'status']);
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade']);

    // Rang & Qualification
    Route::get('/rank/history', [RankController::class, 'history']);
    Route::post('/rank/check', [RankController::class, 'check']);

});
