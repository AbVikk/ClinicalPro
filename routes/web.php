<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\CustomPasswordResetController;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Nurse\NurseController;
use App\Http\Controllers\PaystackWebhookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Paystack Webhook (Must be public)
Route::post('/paystack/webhook', [PaystackWebhookController::class, 'handleWebhook']);

// --- GUEST ROUTES ---
Route::middleware('guest')->group(function () {
    
    // Hospital Registration
    Route::get('/register/hospital', [App\Http\Controllers\Auth\HospitalRegistrationController::class, 'showRegistrationForm'])->name('hospital.register.form');
    Route::post('/register/hospital', [App\Http\Controllers\Auth\HospitalRegistrationController::class, 'register'])->name('hospital.register');
    
    // Hospital User Registration
    Route::get('/register/{hospitalDomain}', [App\Http\Controllers\Auth\HospitalUserRegistrationController::class, 'showRegistrationForm'])->name('hospital.user.register.form');
    Route::post('/register/{hospitalDomain}', [App\Http\Controllers\Auth\HospitalUserRegistrationController::class, 'register'])->name('hospital.user.register');
    
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Registration
    Route::get('/register', [RegistrationController::class, 'showInitialForm'])->name('register.initial');
    Route::post('/register/initial', [RegistrationController::class, 'processInitialForm'])->name('register.process.initial');
    Route::get('/register/otp', [RegistrationController::class, 'showOtpForm'])->name('register.otp');
    Route::post('/register/otp', [RegistrationController::class, 'verifyOtp'])->name('register.verify.otp');
    Route::post('/register/resend', [RegistrationController::class, 'resendOtp'])->name('register.resend');
    Route::get('/register/continue', [RegistrationController::class, 'showContinueForm'])->name('register.continue');
    Route::post('/register/continue', [RegistrationController::class, 'processContinueForm'])->name('register.process.continue');
    
    // Registration Steps (Photo/Proof)
    Route::get('/register/photo/{user_id}', [RegistrationController::class, 'showPhotoForm'])->name('register.photo');
    Route::post('/register/photo', [RegistrationController::class, 'processPhotoForm'])->name('register.process.photo');
    Route::get('/register/proof/{user_id}', [RegistrationController::class, 'showProofForm'])->name('register.proof');
    Route::post('/register/proof', [RegistrationController::class, 'processProofUpload'])->name('register.process.proof');
    Route::get('/register/license/{user_id}', [RegistrationController::class, 'showLicenseForm'])->name('register.license');
    Route::post('/register/license', [RegistrationController::class, 'processLicenseForm'])->name('register.process.license');

    // Password Reset
    Route::get('/forgot-password', function () { return view('auth.forgot-password'); })->name('password.request');
    Route::post('/forgot-password', [CustomPasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/forgot-otp', [CustomPasswordResetController::class, 'showOtpForm'])->name('password.show-otp');
    Route::post('/forgot-otp', [CustomPasswordResetController::class, 'verifyOtp'])->name('password.verify-otp');
    Route::post('/resend-otp', [CustomPasswordResetController::class, 'resendOtp'])->name('password.resend-otp');
    Route::get('/reset-password/{token}', [CustomPasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [CustomPasswordResetController::class, 'resetPassword'])->name('password.update');
    
    // Invitation Registration
    Route::get('/register/invite/{token}', [InvitationController::class, 'showRegistrationForm'])
        ->middleware('signed')
        ->name('invitations.register');
    Route::post('/register/invite/{token}', [InvitationController::class, 'register'])->name('invitations.process');
});

// --- AUTHENTICATED ROUTES ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/logout-page', function () { return view('auth.logout'); })->name('logout.page');

// --- PAYMENT CALLBACKS (PUBLIC) ---
Route::get('/admin/wallet/topup/verify', [PaymentController::class, 'verifyPayment'])->name('admin.payment.verify');
Route::get('/nurse/payments/success', [NurseController::class, 'paymentSuccess'])->name('nurse.payments.success.public');
Route::get('/nurse/payments/failed', [NurseController::class, 'paymentFailed'])->name('nurse.payments.failed.public');
Route::get('/nurse/payments/pending', [NurseController::class, 'paymentPending'])->name('nurse.payments.pending.public');

// --- QUEUE MONITOR (PUBLIC TV) ---
Route::get('/monitor', [App\Http\Controllers\QueueMonitorController::class, 'index'])->name('monitor.index');
Route::get('/monitor/content', [App\Http\Controllers\QueueMonitorController::class, 'content'])->name('monitor.content');

// --- SUPER ADMIN ROUTES ---
Route::middleware(['web', 'auth', 'role:super_admin'])->prefix('super_admin')->name('super_admin.')->group(function () {
    // Hospital Management
    Route::resource('hospitals', App\Http\Controllers\Admin\HospitalManagementController::class);
});

// --- FALLBACK DASHBOARD ---
// If any role hits /pharmacy/dashboard, redirect them to their correct home.
Route::get('/pharmacy/dashboard', function () {
    $role = Auth::user()->role ?? '';
    return match($role) {
        'primary_pharmacist' => redirect()->route('primary_pharmacist.dashboard'),
        'senior_pharmacist'  => redirect()->route('senior_pharmacist.dashboard'),
        'clinic_pharmacist'  => redirect()->route('clinic_pharmacist.sales.index'), // Explicit POS redirect
        default              => abort(404)
    };
})->name('pharmacy.dashboard.fallback');