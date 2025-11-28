<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BroadcastController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::apiResource('activities', ActivityController::class);
    Route::apiResource('broadcasts', BroadcastController::class);
    Route::get('broadcasts/{id}/download-photo', [BroadcastController::class, 'downloadPhoto']);
    Route::get('broadcasts/{id}/download-document', [BroadcastController::class, 'downloadDocument']);

});
