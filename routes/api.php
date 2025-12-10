<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\AspirasiController;
use Illuminate\Support\Facades\Route;

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
    Route::apiResource('incomes', IncomeController::class)->only(['index', 'show', 'store', 'update']);

    Route::post('/aspirasi', [AspirasiController::class, 'store']);
    Route::get('/aspirasi/this-month', [AspirasiController::class, 'thisMonth']);
    Route::get('/aspirasi/my-history', [AspirasiController::class, 'myHistory']);
    Route::get('/aspirasi', [AspirasiController::class, 'index']);
    Route::get('/aspirasi/{id}', [AspirasiController::class, 'show']);
    Route::put('/aspirasi/{id}', [AspirasiController::class, 'update']);
    Route::delete('/aspirasi/{id}', [AspirasiController::class, 'destroy']);
});
