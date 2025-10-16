<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Fondation\Controllers\FondationController;

Route::middleware('auth:sanctum')->prefix('fondations/operations')->group(function () {
    Route::apiResource('fondations', FondationController::class)->only([
        'index',
        'store',
        'show',
        'destroy',
    ]);
});
