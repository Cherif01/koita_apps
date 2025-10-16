<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Comptabilite\Controllers\TypeOperationController;

Route::middleware('auth:sanctum')->prefix('comptabilite')->group(function () {
    Route::apiResource('type-operations', TypeOperationController::class);
});
