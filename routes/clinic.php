<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clinic;

// Clinic staff routes (middleware applied in RouteServiceProvider)
Route::get('/dashboard', [Clinic\DashboardController::class, 'index'])->name('dashboard');