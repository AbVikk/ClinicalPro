<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Admin\InvitationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\CustomPasswordResetController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Nurse\NurseController;
use App\Http\Controllers\PaystackWebhookController;

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

// --- PUBLIC ROUTES ---

Route::get('/', function () {
    return view('welcome');
});

// Paystack Webhook (Must be public)
Route::post('/paystack/webhook', [PaystackWebhookController::class, 'handleWebhook']);

// [Security Cleanup] - Commented out test routes
// Route::post('/test-form', function (\Illuminate\Http\Request $request) { ... });
// Route::get('/test-form', function () { return view('test-form'); });
// Route::get('/test-public-route', function () { return response()->json(['message' => 'Public route working']); });
// Route::get('/test-route', function () { return response()->json(['message' => 'Test route working']); });
// Route::post('/test-doctor-store', function () { return response()->json(['message' => 'Doctor store route working']); })->name('test.doctor.store');
// Route::get('/test-roles-permissions', [App\Http\Controllers\Admin\ClinicStaffController::class, 'rolesPermissions']);


// --- AUTHENTICATION ROUTES (GUEST ONLY) ---
Route::middleware('guest')->group(function () {
    
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Registration (Multi-Step)
    Route::get('/register', [RegistrationController::class, 'showInitialForm'])->name('register.initial');
    Route::post('/register/initial', [RegistrationController::class, 'processInitialForm'])->name('register.process.initial');
    
    Route::get('/register/otp', [RegistrationController::class, 'showOtpForm'])->name('register.otp');
    Route::post('/register/otp', [RegistrationController::class, 'verifyOtp'])->name('register.verify.otp');
    Route::post('/register/resend', [RegistrationController::class, 'resendOtp'])->name('register.resend');
    
    Route::get('/register/continue', [RegistrationController::class, 'showContinueForm'])->name('register.continue');
    Route::post('/register/continue', [RegistrationController::class, 'processContinueForm'])->name('register.process.continue');
    
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
        
    Route::post('/register/invite/{token}', [InvitationController::class, 'register'])
        ->name('invitations.process');
});

// --- AUTHENTICATED ROUTES ---
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // [Security Cleanup] - Commented out Auth Test
    /*
    Route::get('/test-auth', function (\Illuminate\Http\Request $request) {
        return response()->json(['authenticated' => true, 'user_id' => $request->user()->id]);
    })->name('test.auth');
    */
});

// Public Logout View
Route::get('/logout-page', function () {
    return view('auth.logout');
})->name('logout.page');

// --- PAYMENT CALLBACKS (PUBLIC) ---
// Paystack needs to hit these even if the session expired
Route::get('/admin/wallet/topup/verify', [PaymentController::class, 'verifyPayment'])->name('admin.payment.verify');

// Nurse Payment Status Pages
Route::get('/nurse/payments/success', [NurseController::class, 'paymentSuccess'])->name('nurse.payments.success.public');
Route::get('/nurse/payments/failed', [NurseController::class, 'paymentFailed'])->name('nurse.payments.failed.public');
Route::get('/nurse/payments/pending', [NurseController::class, 'paymentPending'])->name('nurse.payments.pending.public');

// --- QUEUE MONITOR (PUBLIC TV) ---
Route::get('/monitor', [App\Http\Controllers\QueueMonitorController::class, 'index'])->name('monitor.index');
Route::get('/monitor/content', [App\Http\Controllers\QueueMonitorController::class, 'content'])->name('monitor.content');

// [Security Cleanup] - Commented out Debug Routes
// Route::get('/admin/wallet/test-webhook', function () { return view('admin.wallet.test_webhook'); });
// Route::get('/test-redis', function () { ... });
// Route::get('/debug-gemini', function() { return (new \App\Services\AiAssistantService)->checkConnection(); });
// Route::get('/test-nurse-success', function() { return view('nurse.payments.success'); });
// Route::get('/debug-nurse-success', function() { ... });
// Route::get('/test-nurse-pending-route', function() { ... });