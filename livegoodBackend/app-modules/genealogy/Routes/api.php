<?php

use Illuminate\Support\Facades\Route;
use Modules\Genealogy\Http\Controllers\GenealogyController;
use Modules\Genealogy\Http\Controllers\MatrixController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->prefix('genealogy')->group(function () {
    
    // Arbre Unilevel (Parrainage direct)
    Route::get('/', [GenealogyController::class, 'index']);
    Route::get('/tree', [GenealogyController::class, 'tree']);
    Route::get('/node/{nodeId}', [GenealogyController::class, 'showNode']);
    Route::get('/search', [GenealogyController::class, 'search']);
    Route::get('/downline/{userId}', [GenealogyController::class, 'downline']);
    Route::get('/upline/{userId}', [GenealogyController::class, 'upline']);
    Route::post('/refresh-rank', [GenealogyController::class, 'refreshRank']);

    // Matrice Forcée 2x15
    Route::get('/matrix/tree', [MatrixController::class, 'viewTree']);
    Route::get('/matrix/node/{nodeId}', [MatrixController::class, 'showNode']);
    Route::get('/matrix/bonus', [MatrixController::class, 'currentBonus']);
    
});
