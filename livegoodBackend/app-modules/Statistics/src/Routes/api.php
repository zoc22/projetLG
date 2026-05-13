<?php

use Illuminate\Support\Facades\Route;
use Modules\Statistics\Http\Controllers\StatisticsController;
use Modules\Statistics\Http\Controllers\VisitTrackerController;

/*
| API Routes for Statistics Module
*/

// Routes publiques (Tracking)
Route::post('/stats/track', [VisitTrackerController::class, 'track']);

// Routes privées (Dashboard)
Route::middleware('auth:api')->prefix('stats')->group(function () {
    Route::get('/summary', [StatisticsController::class, 'summary']);
});
