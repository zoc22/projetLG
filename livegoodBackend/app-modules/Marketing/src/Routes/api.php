<?php

use Illuminate\Support\Facades\Route;
use Modules\Marketing\Http\Controllers\MarketingController;

/*
| API Routes for Marketing Module
*/

// Routes publiques (Capture)
Route::post('/marketing/capture', [MarketingController::class, 'publicCapture']);

// Routes privées (Affiliate Dashboard)
Route::middleware('auth:api')->prefix('marketing')->group(function () {
    Route::get('/sites', [MarketingController::class, 'mySites']);
    Route::get('/leads', [MarketingController::class, 'myLeads']);
});
