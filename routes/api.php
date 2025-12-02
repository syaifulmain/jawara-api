<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\IncomeCategoryController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\TransferChannelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IncomeController;
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
    
    // Income Categories routes
    Route::apiResource('income-categories', IncomeCategoryController::class);
    Route::get('income-categories-types', [IncomeCategoryController::class, 'types']);
    Route::apiResource('transfer-channels', TransferChannelController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::apiResource('incomes', IncomeController::class)->only(['index', 'show', 'store', 'update']);
    Route::apiResource('transfer-channels', TransferChannelController::class);
});
