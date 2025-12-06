<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pharmacy\PharmacyDashboardController;
use App\Http\Controllers\Pharmacy\PharmacySalesController;
use App\Http\Controllers\Pharmacy\InventoryController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Admin\SeniorPharmacistController;

// 1. Dashboard (Scoped to Clinic by Service)
Route::get('/dashboard', [PharmacyDashboardController::class, 'index'])->name('dashboard');

// 2. POS / Sales (Scoped to Clinic by Service)
Route::prefix('sales')->name('sales.')->group(function() {
    Route::get('/', [PharmacySalesController::class, 'index'])->name('index');
    Route::get('/search-patient', [PharmacySalesController::class, 'searchPatient'])->name('search-patient');
    Route::get('/search-drugs', [PharmacySalesController::class, 'searchDrugs'])->name('search-drugs');
    Route::get('/load-prescription/{prescription}', [PharmacySalesController::class, 'loadPrescription'])->name('load-prescription');
    Route::post('/process-sale', [PharmacySalesController::class, 'processSale'])->name('process-sale');
    Route::post('/create-walkin', [PharmacySalesController::class, 'createWalkIn'])->name('create-walkin');
    Route::get('/receipt/{order}', [PharmacySalesController::class, 'generateReceipt'])->name('receipt');
});

// 3. Inventory Operations
Route::get('/inventory/predictions', [InventoryController::class, 'index'])->name('inventory.predictions');
Route::post('/clinic/request-stock', [SeniorPharmacistController::class, 'requestStock'])->name('clinic.request-stock');
Route::post('/clinic/transfer/receive/{id}', [SeniorPharmacistController::class, 'receiveStock'])->name('clinic.transfer.receive');
Route::get('/clinic/alerts', [SeniorPharmacistController::class, 'getLowStockAlerts'])->name('clinic.alerts');

// 4. Invitations (Can invite Clinic Pharmacists)
Route::get('/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');