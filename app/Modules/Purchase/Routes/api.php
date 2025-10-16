<?php

use App\Modules\Purchase\Controllers\AchatController;
use App\Modules\Purchase\Controllers\LotController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1/gestion')->group(function(){
    // Gestion des lots
    Route::get('lots/status/{id}', [LotController::class, 'status']);
    Route::get('lots/restore/{id}', [LotController::class, 'restore']);
    Route::get('lots/delete/{id}', [LotController::class, 'forceDelete']);
    Route::apiResource('lots', LotController::class);

    // Gestion des achats
    Route::get('achats/status/{id}', [AchatController::class, 'status']);
    Route::get('achats/restore/{id}', [AchatController::class, 'restore']);
    Route::get('achats/delete/{id}', [AchatController::class, 'forceDelete']);
    Route::apiResource('achats', AchatController::class);
});
