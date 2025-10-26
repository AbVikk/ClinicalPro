<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Matron;

// Matron routes (middleware applied in RouteServiceProvider)
Route::get('/dashboard', [Matron\StaffManagementController::class, 'index'])->name('dashboard');

// Staff management routes
Route::get('/staff', [Matron\StaffManagementController::class, 'index'])->name('staff.index');
Route::post('/staff/assign', [Matron\StaffManagementController::class, 'assignStaff'])->name('staff.assign');
Route::post('/staff/remove', [Matron\StaffManagementController::class, 'removeStaff'])->name('staff.remove');