<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pharmacy\PharmacyDashboardController;
use App\Http\Controllers\Pharmacy\PharmacySalesController;
use App\Http\Controllers\Pharmacy\InventoryController;
use App\Http\Controllers\Pharmacy\PrimaryPharmacist\InvitationController;
use App\Http\Controllers\Pharmacy\PrimaryPharmacist\PharmacistController;
use App\Http\Controllers\Pharmacy\PrimaryPharmacist\SoldOrdersController;
use App\Http\Controllers\Admin\DrugCategoryController;
use App\Http\Controllers\Admin\DrugMgController;
use App\Http\Controllers\Admin\PrimaryPharmacistController;
use App\Http\Controllers\Admin\SeniorPharmacistController;
use App\Http\Controllers\Admin\ClinicPharmacistController;
use App\Http\Controllers\Pharmacy\PrimaryPharmacist\PrescriptionController; // Added Import

// 1. Dashboard
Route::get('/dashboard', [PharmacyDashboardController::class, 'index'])->name('dashboard');
Route::post('/inventory/run-analysis', [PharmacyDashboardController::class, 'runAiAnalysis'])->name('inventory.run-analysis');

// 2. POS / Sales
Route::prefix('sales')->name('sales.')->group(function() {
    Route::get('/', [PharmacySalesController::class, 'index'])->name('index');
    Route::get('/search-patient', [PharmacySalesController::class, 'searchPatient'])->name('search-patient');
    Route::get('/search-drugs', [PharmacySalesController::class, 'searchDrugs'])->name('search-drugs');
    Route::get('/load-prescription/{prescription}', [PharmacySalesController::class, 'loadPrescription'])->name('load-prescription');
    Route::post('/process-sale', [PharmacySalesController::class, 'processSale'])->name('process-sale');
    Route::post('/create-walkin', [PharmacySalesController::class, 'createWalkIn'])->name('create-walkin');
    Route::get('/receipt/{order}', [PharmacySalesController::class, 'generateReceipt'])->name('receipt');
});

// 3. Inventory & Management
Route::get('/inventory/predictions', [InventoryController::class, 'index'])->name('inventory.predictions');
Route::get('/sold-orders', [SoldOrdersController::class, 'index'])->name('sold-orders.index');

// 4. Staff
Route::get('/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');
Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');
Route::resource('/pharmacists', PharmacistController::class);

// 5. Drug Catalog Management (THIS IS THE SECTION FIXING YOUR ERROR)
Route::get('/pharmacy/drugs/all', [PrimaryPharmacistController::class, 'showAllDrugs'])->name('pharmacy.drugs.all');
Route::get('/pharmacy/drugs/create-form', [PrimaryPharmacistController::class, 'showCreateDrugForm'])->name('pharmacy.drugs.create.form');
Route::post('/pharmacy/drugs/create', [PrimaryPharmacistController::class, 'createDrug'])->name('pharmacy.drugs.create');
Route::get('/pharmacy/drugs/{id}', [PrimaryPharmacistController::class, 'viewDrug'])->name('pharmacy.drugs.view');

// --- THE MISSING ROUTES ---
Route::get('/pharmacy/drugs/{id}/edit', [PrimaryPharmacistController::class, 'editDrug'])->name('pharmacy.drugs.edit');
Route::put('/pharmacy/drugs/{id}', [PrimaryPharmacistController::class, 'updateDrug'])->name('pharmacy.drugs.update');
// --------------------------

Route::delete('/pharmacy/drugs/{id}', [PrimaryPharmacistController::class, 'deleteDrug'])->name('pharmacy.drugs.delete');
Route::get('/pharmacy/drugs/{id}/history', [PrimaryPharmacistController::class, 'getDrugHistory'])->name('pharmacy.drugs.history');

// 6. Stock Operations
Route::post('/pharmacy/stock/receive', [PrimaryPharmacistController::class, 'receiveStock'])->name('pharmacy.stock.receive');
Route::post('/pharmacy/stock/update', [PrimaryPharmacistController::class, 'updateStock'])->name('pharmacy.stock.update');
Route::post('/pharmacy/transfers/approve/{id}', [PrimaryPharmacistController::class, 'approveTransfer'])->name('pharmacy.transfers.approve');

// Clinic Routes
Route::post('/clinic/request-stock', [SeniorPharmacistController::class, 'requestStock'])->name('clinic.request-stock');
Route::post('/clinic/transfer/receive/{id}', [SeniorPharmacistController::class, 'receiveStock'])->name('clinic.transfer.receive');
Route::get('/clinic/alerts', [SeniorPharmacistController::class, 'getLowStockAlerts'])->name('clinic.alerts');
Route::post('/clinic/sell', [ClinicPharmacistController::class, 'sell'])->name('clinic.sell');

// 7. Categories & MG
Route::resource('/pharmacy/categories', DrugCategoryController::class, ['as' => 'pharmacy']);
Route::resource('/pharmacy/mg', DrugMgController::class, ['as' => 'pharmacy']);

// 8. Prescriptions
Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions.index');
Route::get('/prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');
Route::get('/prescriptions/{prescription}/print', [PrescriptionController::class, 'printPrescription'])->name('prescriptions.print');