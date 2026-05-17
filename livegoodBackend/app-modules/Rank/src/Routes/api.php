<?php

use Illuminate\Support\Facades\Route;
use Modules\Rank\Http\Controllers\RankController;

Route::middleware('auth:sanctum')->prefix('ranks')->group(function () {
    Route::get('/me', [RankController::class, 'myRank']);
    Route::post('/refresh', [RankController::class, 'refresh']);
});
