<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pharmacy;

// Pharmacy routes (middleware applied in RouteServiceProvider)
Route::get('/dashboard', [Pharmacy\DashboardController::class, 'index'])->name('dashboard');