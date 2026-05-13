<?php

use Illuminate\Support\Facades\Route;
use Modules\Training\Http\Controllers\TrainingController;
use Modules\Training\Http\Controllers\WebinarController;

/*
| API Routes for Training Module
*/

Route::middleware('auth:sanctum')->prefix('training')->group(function () {
    
    // Formations
    Route::get('/courses', [TrainingController::class, 'index']);
    Route::get('/my-courses', [TrainingController::class, 'myTrainings']);
    Route::post('/courses/{id}/enroll', [TrainingController::class, 'enroll']);
    Route::post('/courses/{id}/progress', [TrainingController::class, 'progress']);

    // Webinaires
    Route::get('/webinars', [WebinarController::class, 'index']);
    Route::get('/webinars/replays', [WebinarController::class, 'replays']);

});
