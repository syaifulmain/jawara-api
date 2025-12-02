<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengeluaranController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::apiResource('activities', ActivityController::class);
    Route::apiResource('broadcasts', BroadcastController::class);
    Route::get('broadcasts/{id}/download-photo', [BroadcastController::class, 'downloadPhoto']);
    Route::get('broadcasts/{id}/download-document', [BroadcastController::class, 'downloadDocument']);

    Route::apiResource('residents', ResidentController::class)->only(['index', 'show', 'store', 'update']);
    Route::apiResource('families', FamilyController::class)->only(['index', 'show']);
    Route::apiResource('addresses', AddressController::class)->only(['index', 'show', 'store']);
    Route::apiResource('users', UserController::class)->only(['index', 'show']);
    Route::apiResource('pengeluaran', PengeluaranController::class)->only(['index', 'show', 'store']);
});
