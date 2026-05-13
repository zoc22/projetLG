<?php

namespace Modules\Commission\Routes;

use Illuminate\Support\Facades\Route;
use Modules\Commission\Http\Controllers\CommissionController;
use Modules\Commission\Http\Controllers\BonusController;

Route::middleware('auth:api')->prefix('commissions')->group(function () {
    Route::get('/', [CommissionController::class, 'index']);
    Route::get('/summary/{period}', [CommissionController::class, 'summaryPerPeriod']);
    Route::get('/report/bonus', [BonusController::class, 'reportByType']);
});
