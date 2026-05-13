<?php

use Illuminate\Support\Facades\Route;
use Modules\Genealogy\Http\Controllers\GenealogyController;
use Modules\Genealogy\Http\Controllers\MatrixController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->prefix('genealogy')->group(function () {
    
    // Arbre Unilevel (Parrainage direct)
    Route::get('/', [GenealogyController::class, 'index']);
    Route::post('/refresh-rank', [GenealogyController::class, 'refreshRank']);

    // Matrice Forcée 2x15
    Route::get('/matrix/tree', [MatrixController::class, 'viewTree']);
    Route::get('/matrix/bonus', [MatrixController::class, 'currentBonus']);
    
});
