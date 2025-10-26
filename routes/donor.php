<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Donor;

// Donor routes (middleware applied in RouteServiceProvider)
Route::get('/dashboard', [Donor\DashboardController::class, 'index'])->name('dashboard');