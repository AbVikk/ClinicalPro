<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Nurse\NurseController;
use App\Http\Controllers\Api\AiController;

/*
|--------------------------------------------------------------------------
| Nurse Routes
|--------------------------------------------------------------------------
*/

// Test route to check if nurse routes are working
Route::get('/test-success', function () {
    return view('nurse.payments.success');
})->name('test-success');

/*
|--------------------------------------------------------------------------
| AI Chat API Routes
|--------------------------------------------------------------------------
*/
// These routes are automatically prefixed with 'nurse.' by the RouteServiceProvider
Route::prefix('ai')->name('api.ai.')->group(function () {
    Route::post('/scheduling', [AiController::class, 'getSmartScheduling'])
        ->name('scheduling');

    Route::post('/extract-note-details', [AiController::class, 'extractDetailsFromNote'])
        ->name('extract-notes');

    Route::get('/chat-history', [AiController::class, 'getChatHistory'])
        ->name('chat-history');

    Route::delete('/chat-history/clear', [AiController::class, 'clearChatHistory'])
        ->name('chat-history.clear');
});

// --- Your Existing Routes ---
Route::get('dashboard', [NurseController::class, 'dashboard'])->name('dashboard');
Route::get('dashboard/clear-cache', [NurseController::class, 'clearDashboardCache'])->name('dashboard.clear-cache');
Route::get('data/queue', [NurseController::class, 'ajaxGetQueue'])->name('ajax.queue');
Route::get('data/doctors', [NurseController::class, 'ajaxGetDoctorStatus'])->name('ajax.doctors');
Route::post('appointment/{appointment}/save-vitals', [NurseController::class, 'saveVitals'])->name('vitals.save');

// Patients route
Route::get('patients', [NurseController::class, 'patientsIndex'])->name('patients.index');

// --- **NEW** BOOK APPOINTMENT ROUTES ---
Route::get('/book-appointment', [NurseController::class, 'bookAppointment'])->name('book-appointment');
Route::post('/book-appointment/patient-info', [NurseController::class, 'getPatientInfo'])->name('book-appointment.patient-info');
Route::post('/book-appointment/available-doctors', [NurseController::class, 'getAvailableDoctors'])->name('book-appointment.available-doctors');
Route::post('/book-appointment/available-locations', [NurseController::class, 'getAvailableLocations'])->name('book-appointment.available-locations');
Route::post('/book-appointment/search-patients', [NurseController::class, 'searchPatients'])->name('book-appointment.search-patients');
Route::post('/book-appointment', [NurseController::class, 'storeAppointment'])->name('book-appointment.store');
Route::post('/book-appointment/walk-in-patient', [NurseController::class, 'storeWalkInPatient'])->name('book-appointment.walk-in-patient');
Route::post('/book-appointment/service-time-pricing', [NurseController::class, 'getServiceTimePricing'])->name('book-appointment.service-time-pricing');

// --- **NEW** PAYMENT & INVOICE ROUTES ---
// We can create a new controller for this later, but for now, the NurseController can handle it.
Route::get('/payments', [NurseController::class, 'paymentIndex'])->name('payments.index');
Route::get('/payments/create', [NurseController::class, 'paymentCreate'])->name('payments.create');
Route::post('/payments', [NurseController::class, 'paymentStore'])->name('payments.store');
Route::get('/payments/{payment}', [NurseController::class, 'paymentShow'])->name('payments.show');
Route::get('/payments/{payment}/edit', [NurseController::class, 'paymentEdit'])->name('payments.edit');
Route::put('/payments/{payment}', [NurseController::class, 'paymentUpdate'])->name('payments.update');
Route::delete('/payments/{payment}', [NurseController::class, 'paymentDestroy'])->name('payments.destroy');
Route::get('/payments/invoice/{payment}', [NurseController::class, 'paymentInvoice'])->name('payments.invoice');

// Appointment Payment Routes
Route::get('/appointment/payment/initialize', [NurseController::class, 'showAppointmentPayment'])->name('appointment.payment.initialize');

// Payment Status Pages
// These routes need to be public as they are accessed by Paystack callbacks
Route::get('/payments/success', [NurseController::class, 'paymentSuccess'])
     ->name('payments.success');

Route::get('/payments/failed', [NurseController::class, 'paymentFailed'])
     ->name('payments.failed');

Route::get('/payments/pending', [NurseController::class, 'paymentPending'])
     ->name('payments.pending');

Route::post('/payments/paystack/initialize', [NurseController::class, 'initializePaystack'])->name('payments.paystack.initialize');
