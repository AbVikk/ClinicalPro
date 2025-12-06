<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pharmacy\PharmacySalesController;
use App\Http\Controllers\Pharmacy\InventoryController;

// 1. Dashboard -> Redirects to POS (Strict Requirement)
Route::get('/dashboard', [PharmacySalesController::class, 'index'])->name('dashboard');

// 2. POS / Sales System
Route::prefix('sales')->name('sales.')->group(function() {
    Route::get('/', [PharmacySalesController::class, 'index'])->name('index');
    Route::get('/search-patient', [PharmacySalesController::class, 'searchPatient'])->name('search-patient'); 
    Route::get('/search-drugs', [PharmacySalesController::class, 'searchDrugs'])->name('search-drugs'); 
    Route::get('/load-prescription/{prescription}', [PharmacySalesController::class, 'loadPrescription'])->name('load-prescription');
    Route::post('/process-sale', [PharmacySalesController::class, 'processSale'])->name('process-sale');
    Route::post('/create-walkin', [PharmacySalesController::class, 'createWalkIn'])->name('create-walkin'); 
    Route::get('/receipt/{order}', [PharmacySalesController::class, 'generateReceipt'])->name('receipt'); 
});

// 3. Inventory (View Only / Receiving)
// They can view predictions but not manage stock levels directly
Route::get('/inventory/predictions', [InventoryController::class, 'index'])->name('inventory.predictions');