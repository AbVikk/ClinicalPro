<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Test route for debugging form submission
Route::post('/test-form', function (\Illuminate\Http\Request $request) {
    Log::info('Test form submission received', [
        'all_data' => $request->all(),
        'files' => $request->file(),
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Form data received successfully',
        'data' => $request->except('_token'),
    ]);
})->name('test.form');

Route::get('/test-form', function () {
    return view('test-form');
});

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-public-route', function () {
    return response()->json(['message' => 'Public route working']);
});

// Paystack webhook route (public, no authentication)
Route::post('/paystack/webhook', [App\Http\Controllers\PaystackWebhookController::class, 'handleWebhook']);

// Test route to check if routing is working
Route::get('/test-route', function () {
    return response()->json(['message' => 'Test route working']);
});

// Test route to check if doctor store route is working
Route::post('/test-doctor-store', function () {
    return response()->json(['message' => 'Doctor store route working']);
})->name('test.doctor.store');

// Test route to check if the rolesPermissions method is working
Route::get('/test-roles-permissions', [App\Http\Controllers\Admin\ClinicStaffController::class, 'rolesPermissions']);

// Public invitation routes
Route::get('/register/invite/{token}', [App\Http\Controllers\Admin\InvitationController::class, 'showRegistrationForm'])
    ->middleware('guest', 'signed')
    ->name('invitations.register');
Route::post('/register/invite/{token}', [App\Http\Controllers\Admin\InvitationController::class, 'register'])
    ->middleware('guest')
    ->name('invitations.process');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.sign-in');
    })->name('login');
    
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
    
    // New multi-step registration routes
    Route::get('/register', [App\Http\Controllers\Auth\RegistrationController::class, 'showInitialForm'])->name('register.initial');
    Route::post('/register/initial', [App\Http\Controllers\Auth\RegistrationController::class, 'processInitialForm'])->name('register.process.initial');
    Route::get('/register/otp', [App\Http\Controllers\Auth\RegistrationController::class, 'showOtpForm'])->name('register.otp');
    Route::post('/register/otp', [App\Http\Controllers\Auth\RegistrationController::class, 'verifyOtp'])->name('register.verify.otp');
    Route::get('/register/continue', [App\Http\Controllers\Auth\RegistrationController::class, 'showContinueForm'])->name('register.continue');
    Route::post('/register/continue', [App\Http\Controllers\Auth\RegistrationController::class, 'processContinueForm'])->name('register.process.continue');
    Route::get('/register/photo/{user_id}', [App\Http\Controllers\Auth\RegistrationController::class, 'showPhotoForm'])->name('register.photo');
    Route::post('/register/photo', [App\Http\Controllers\Auth\RegistrationController::class, 'processPhotoForm'])->name('register.process.photo');
    Route::get('/register/proof/{user_id}', [App\Http\Controllers\Auth\RegistrationController::class, 'showProofForm'])->name('register.proof');
    Route::post('/register/proof', [App\Http\Controllers\Auth\RegistrationController::class, 'processProofUpload'])->name('register.process.proof');
    Route::get('/register/license/{user_id}', [App\Http\Controllers\Auth\RegistrationController::class, 'showLicenseForm'])->name('register.license');
    Route::post('/register/license', [App\Http\Controllers\Auth\RegistrationController::class, 'processLicenseForm'])->name('register.process.license');
});

// Logout routes (accessible to everyone)
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/logout-page', function () {
    return view('auth.logout');
})->name('logout.page');

// Password reset routes
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
    
    Route::post('/forgot-password', [App\Http\Controllers\Auth\CustomPasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    
    Route::get('/forgot-otp', [App\Http\Controllers\Auth\CustomPasswordResetController::class, 'showOtpForm'])->name('password.show-otp');
    Route::post('/forgot-otp', [App\Http\Controllers\Auth\CustomPasswordResetController::class, 'verifyOtp'])->name('password.verify-otp');
    Route::post('/resend-otp', [App\Http\Controllers\Auth\CustomPasswordResetController::class, 'resendOtp'])->name('password.resend-otp');
    
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\CustomPasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\CustomPasswordResetController::class, 'resetPassword'])->name('password.update');
});

// Payment verification route (must be outside auth middleware for Paystack callbacks)
Route::get('/admin/wallet/topup/verify', [App\Http\Controllers\Admin\PaymentController::class, 'verifyPayment'])->name('admin.payment.verify');
Route::get('/admin/wallet/test-webhook', function () {
    return view('admin.wallet.test_webhook');
})->name('admin.wallet.test-webhook');
