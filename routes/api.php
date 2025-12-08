<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\IncomeCategoryController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\TransferChannelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengeluaranController;
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
    Route::get('/residents/user/{userId}', [ResidentController::class, 'getByUserId']);
    Route::apiResource('families', FamilyController::class)->only(['index', 'show']);
    Route::apiResource('addresses', AddressController::class)->only(['index', 'show', 'store']);
    Route::apiResource('users', UserController::class)->only(['index', 'show']);
    Route::apiResource('pengeluaran', PengeluaranController::class)->only(['index', 'show', 'store']);
    
    // Income Categories routes
    Route::apiResource('income-categories', IncomeCategoryController::class);
    Route::get('income-categories-types', [IncomeCategoryController::class, 'types']);
    Route::apiResource('transfer-channels', TransferChannelController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    
    // Bills routes
    Route::apiResource('bills', BillController::class);
    Route::post('bills/generate', [BillController::class, 'generateBills']);
    Route::patch('bills/{id}/upload-payment', [BillController::class, 'uploadPaymentProof']);
    Route::patch('bills/{id}/approve-payment', [BillController::class, 'approvePayment']);
    Route::patch('bills/{id}/reject-payment', [BillController::class, 'rejectPayment']);
    Route::get('bills/statistics', [BillController::class, 'statistics']);
    Route::post('bills/mark-overdue', [BillController::class, 'markOverdue']);
    Route::apiResource('incomes', IncomeController::class)->only(['index', 'show', 'store', 'update']);
    Route::apiResource('transfer-channels', TransferChannelController::class);
});
