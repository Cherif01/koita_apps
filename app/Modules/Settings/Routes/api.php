<?php

use App\Modules\Settings\Controllers\BanqueController;
use Illuminate\Support\Facades\Route;
use App\Modules\Settings\Controllers\ClientController;
use App\Modules\Settings\Controllers\DeviseController;
use App\Modules\Settings\Controllers\MonetaireController;
use App\Modules\Settings\Controllers\PartenaireController;

Route::middleware('auth:sanctum')->prefix('settings/')->group(function () {
    Route::apiResource('clients', ClientController::class);
     Route::apiResource('partenaires', PartenaireController::class);
     Route::apiResource('devises', DeviseController::class);
     Route::apiResource('monetaires', MonetaireController::class);
     Route::apiResource('banques', BanqueController::class);
});
