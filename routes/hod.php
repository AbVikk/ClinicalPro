<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HOD;

// HOD routes (middleware applied in RouteServiceProvider)
Route::get('/dashboard', [HOD\DashboardController::class, 'index'])->name('dashboard');

// Doctor management routes
Route::get('/doctors', [HOD\DoctorManagementController::class, 'index'])->name('doctors.index');
Route::post('/doctors/assign', [HOD\DoctorManagementController::class, 'assignDoctor'])->name('doctors.assign');
Route::post('/doctors/remove', [HOD\DoctorManagementController::class, 'removeDoctor'])->name('doctors.remove');