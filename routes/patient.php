<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Patient;

// Patient routes (middleware applied in RouteServiceProvider)
Route::get('/dashboard', [Patient\DashboardController::class, 'index'])->name('dashboard');

// PWA Pharmacy routes
Route::get('/pwa/pharmacy', [App\Http\Controllers\PwaPharmacyController::class, 'index'])->name('pwa.pharmacy.index');
Route::get('/pwa/pharmacy/search', [App\Http\Controllers\PwaPharmacyController::class, 'search'])->name('pwa.pharmacy.search');