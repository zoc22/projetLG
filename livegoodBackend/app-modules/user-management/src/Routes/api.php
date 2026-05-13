<?php

use Illuminate\Support\Facades\Route;
use Modules\UserManagement\Http\Controllers\UserController;

/*
| API Routes for User Manager Module
*/

Route::middleware(['auth:sanctum', 'auth.admin'])->prefix('admin/users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::patch('/{id}/status', [UserController::class, 'updateStatus']);
    Route::patch('/{id}/sponsor', [UserController::class, 'changeSponsor']);
});
