<?php

use Illuminate\Support\Facades\Route;
use Modules\Support\Http\Controllers\SupportController;

/*
| API Routes for Support Module
*/

Route::middleware('auth:sanctum')->prefix('support')->group(function () {
    Route::get('/tickets', [SupportController::class, 'index']);
    Route::post('/tickets', [SupportController::class, 'store']);
    Route::post('/tickets/{id}/reply', [SupportController::class, 'reply']);
    Route::patch('/tickets/{id}/close', [SupportController::class, 'close']);
});
