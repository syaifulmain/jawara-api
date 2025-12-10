<?php

use App\Http\Controllers\DashboardKegiatanController;
use App\Http\Controllers\DashboardKependudukanController;
use App\Http\Controllers\DashboardKeuanganController;
use App\Http\Controllers\LaporanKeuanganController;
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
use App\Http\Controllers\UserFamilyController;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile/{id}', [AuthController::class, 'updateProfile']); // Route diubah
    Route::apiResource('activities', ActivityController::class);
    Route::get('/activities-this-month', [ActivityController::class, 'getActivityInThisMonth']);
    Route::apiResource('broadcasts', BroadcastController::class);
    Route::get('/broadcast-this-week', [BroadcastController::class, 'getBroadcastThisWeek']);
    Route::get('broadcasts/{id}/download-photo', [BroadcastController::class, 'downloadPhoto']);
    Route::get('broadcasts/{id}/download-document', [BroadcastController::class, 'downloadDocument']);

    Route::apiResource('residents', ResidentController::class)->only(['index', 'show', 'store', 'update']);
    Route::get('/residents/user/{userId}', [ResidentController::class, 'getByUserId']);
    Route::apiResource('families', FamilyController::class)->only(['index', 'show']);
    Route::apiResource('addresses', AddressController::class)->only(['index', 'show', 'store']);
    Route::apiResource('pengeluaran', PengeluaranController::class)->only(['index', 'show', 'store']);

    Route::apiResource('users', UserController::class)->only(['index', 'show', 'store']);

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

    Route::get('/user/family', [UserFamilyController::class, 'myFamily']);

    Route::get('/dashboard/keuangan', [DashboardKeuanganController::class, 'index']);
    Route::get('/dashboard/kegiatan', [DashboardKegiatanController::class, 'index']);
    Route::get('/dashboard/kependudukan', [DashboardKependudukanController::class, 'index']);

    Route::get('/reports/financial', [LaporanKeuanganController::class, 'cetakLaporan']);
    Route::get('/reports/financial/download-pdf', [LaporanKeuanganController::class, 'downloadPdf']);
});
