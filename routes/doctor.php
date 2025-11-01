<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor;

// Doctor routes (middleware applied in RouteServiceProvider)
Route::get('/dashboard', [Doctor\DashboardController::class, 'index'])->name('dashboard');

// Appointments routes
Route::get('/appointments', [Doctor\DashboardController::class, 'appointments'])->name('appointments');

// Appointment details routes
Route::get('/appointments/{appointment}/details', [Doctor\DashboardController::class, 'showAppointmentDetails'])->name('appointments.details');
Route::post('/appointments/{appointment}/save-details', [Doctor\DashboardController::class, 'saveAppointmentDetails'])->name('appointments.save-details');

// Lab test routes
Route::post('/lab-tests/{labTest}/upload-result', [Doctor\DashboardController::class, 'uploadLabTestResult'])->name('lab-tests.upload-result');

// Notification routes
Route::post('/notifications/mark-as-read', [Doctor\DashboardController::class, 'markNotificationsAsRead'])->name('doctor.notifications.mark-as-read');
Route::post('/notifications/{notification}/mark-as-read', [Doctor\DashboardController::class, 'markNotificationAsRead'])->name('doctor.notifications.mark-as-read-single');

// Requests routes
Route::get('/requests', [Doctor\DashboardController::class, 'requests'])->name('requests');
Route::post('/requests/{appointment}/accept', [Doctor\DashboardController::class, 'acceptRequest'])->name('requests.accept');
Route::post('/requests/{appointment}/reject', [Doctor\DashboardController::class, 'rejectRequest'])->name('requests.reject');
Route::get('/requests/count', [Doctor\DashboardController::class, 'getRequestCount'])->name('requests.count');

// Patient routes
Route::get('/patients', [Doctor\DashboardController::class, 'indexPatient'])->name('patient.index');
Route::get('/patients/{patient}', [Doctor\DashboardController::class, 'showPatient'])->name('patient.show');

// Doctor Schedule
Route::get('/schedule', [Doctor\DashboardController::class, 'schedule'])->name('schedule');
Route::post('/schedule/save', [Doctor\DashboardController::class, 'saveSchedule'])->name('doctor.schedule.save');
// Route for checking follow-up availability via AJAX
Route::post('/appointments/check-followup-availability', [Doctor\DashboardController::class, 'checkFollowUpAvailability'])->name('appointments.check-followup');

// Appointment session routes
Route::post('/appointments/{appointment}/start', [Doctor\DashboardController::class, 'startAppointment'])->name('appointments.start');
Route::post('/appointments/{appointment}/end', [Doctor\DashboardController::class, 'endAppointment'])->name('appointments.end');
// Route::get('/appointments/{appointment}/details', [Doctor\DashboardController::class, 'showAppointmentDetails'])->name('appointments.details');
// Route::post('/appointments/{appointment}/save-details', [Doctor\DashboardController::class, 'saveAppointmentDetails'])->name('appointments.save-details');
Route::get('/patients/{patient}/appointment-history', [Doctor\DashboardController::class, 'showAppointmentHistory'])->name('patients.appointment-history');
Route::get('/appointments/{appointment}/end', [Doctor\DashboardController::class, 'endAppointment'])->name('appointments.end.get');

// Leave Management routes
Route::get('/leaves', [Doctor\DashboardController::class, 'leaves'])->name('leaves');
Route::post('/leaves', [Doctor\DashboardController::class, 'storeLeave'])->name('leaves.store');
Route::put('/leaves/{leave}', [Doctor\DashboardController::class, 'updateLeave'])->name('leaves.update');
Route::delete('/leaves/{leave}', [Doctor\DashboardController::class, 'deleteLeave'])->name('leaves.delete');

// Prescription routes
Route::get('/prescriptions', [Doctor\DashboardController::class, 'prescriptions'])->name('prescriptions');
Route::get('/prescriptions/{prescription}', [Doctor\DashboardController::class, 'showPrescription'])->name('prescriptions.show');
Route::delete('/prescriptions/{prescription}', [Doctor\DashboardController::class, 'deletePrescription'])->name('prescriptions.delete');


Route::get('/ajax/request-count', [Doctor\DashboardController::class, 'ajaxGetRequestCount'])->name('ajax.requestCount');
Route::get('/ajax/notification-count', [Doctor\DashboardController::class, 'ajaxGetNotificationCount'])->name('ajax.notificationCount');

