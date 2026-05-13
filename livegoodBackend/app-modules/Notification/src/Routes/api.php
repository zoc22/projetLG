<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\NotificationController;

Route::middleware('auth:api')->prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::patch('/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::get('/preferences', [NotificationController::class, 'getPreferences']);
    Route::post('/preferences', [NotificationController::class, 'updatePreferences']);
});
