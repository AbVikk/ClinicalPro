<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// IMPORTANT: THESE ROUTES ARE ONLY LOADED IN 'local' or 'staging' ENVIRONMENTS.

// Test route to check if Vite manifest issue is fixed
Route::get('/test-vite', function () {
    return view('test-vite');
});

// Test route
Route::get('/test', function () {
    return 'Test route working';
});

// Test route to view OTP (for development only)
Route::get('/test-otp', function () {
    $tokens = DB::table('password_reset_tokens')->get();
    return response()->json($tokens);
});

// Pharmacy test route
Route::get('/test-pharmacy', function () {
    // Create a drug
    $drug = \App\Models\Drug::create([
        'name' => 'Test Drug',
        'category' => 'Test Category',
        'strength_mg' => '100mg',
        'unit_price' => 25.00,
        'is_controlled' => false,
    ]);

    return response()->json([
        'message' => 'Pharmacy system is working',
        'drug' => $drug
    ]);
});

// Doctor test calendar route (assuming this is for testing views)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/doctor/test-calendar', function () {
        return view('admin.doctor.test_calendar');
    })->name('admin.doctor.test-calendar');
});