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

// Clean Appointments routes for testing
Route::get('/appointments-clean', function () {
    // Get the authenticated doctor's ID
    $doctorId = \Illuminate\Support\Facades\Auth::user()->id;
    
    // Get filter parameters
    $search = request()->get('search');
    $filter = request()->get('filter', 'all'); // all, chat, direct
    $tab = request()->get('tab', 'upcoming'); // upcoming, cancelled, completed

    // Check if this is an AJAX request for a specific tab
    if (request()->ajax() || request()->get('ajax')) {
        // Build the base query for appointments
        $baseQuery = \App\Models\Appointment::with(['patient.patient', 'appointmentReason'])
            ->where('doctor_id', $doctorId);
            
        // Apply search filter
        if ($search) {
            $baseQuery->where(function($q) use ($search) {
                $q->whereHas('patient', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        
        // Apply type filter
        if ($filter !== 'all') {
            $baseQuery->where('type', $filter);
        }
        
        // Clone the base query for each tab
        $upcomingQuery = clone $baseQuery;
        $inProgressQuery = clone $baseQuery;
        $cancelledQuery = clone $baseQuery;
        $completedQuery = clone $baseQuery;
        
        // Apply status filters for each tab
        $upcomingQuery->where('status', 'confirmed')
                      ->where('appointment_time', '>', now());
        
        $inProgressQuery->where('status', 'in_progress');
        $cancelledQuery->where('status', 'cancelled');
        $completedQuery->where('status', 'completed');
        
        // Get paginated results for the active tab
        $perPage = 10;
        switch ($tab) {
            case 'inprogress':
                $appointments = $inProgressQuery->orderBy('appointment_time', 'asc')->paginate($perPage)->appends(['tab' => 'inprogress', 'search' => $search, 'filter' => $filter]);
                break;
            case 'cancelled':
                $appointments = $cancelledQuery->orderBy('appointment_time', 'desc')->paginate($perPage)->appends(['tab' => 'cancelled', 'search' => $search, 'filter' => $filter]);
                break;
            case 'completed':
                $appointments = $completedQuery->orderBy('appointment_time', 'desc')->paginate($perPage)->appends(['tab' => 'completed', 'search' => $search, 'filter' => $filter]);
                break;
            case 'upcoming':
            default:
                $appointments = $upcomingQuery->orderBy('appointment_time', 'asc')->paginate($perPage)->appends(['tab' => 'upcoming', 'search' => $search, 'filter' => $filter]);
                break;
        }
        
        // Get counts for all tabs (without pagination)
        $upcomingCount = $upcomingQuery->count();
        $cancelledCount = $cancelledQuery->count();
        $completedCount = $completedQuery->count();
        
        return view('doctor.appointments_clean', compact(
            'appointments',
            'upcomingCount', 
            'cancelledCount', 
            'completedCount',
            'search',
            'filter',
            'tab'
        ));
    }

    return view('doctor.appointments_clean');

})->name('appointments.clean');

// Requests routes
Route::get('/requests', [Doctor\DashboardController::class, 'requests'])->name('requests');
Route::post('/requests/{appointment}/accept', [Doctor\DashboardController::class, 'acceptRequest'])->name('requests.accept');
Route::post('/requests/{appointment}/reject', [Doctor\DashboardController::class, 'rejectRequest'])->name('requests.reject');
Route::get('/requests/count', [Doctor\DashboardController::class, 'getRequestCount'])->name('requests.count');

// Patient routes
Route::get('/patients', [Doctor\DashboardController::class, 'indexPatient'])->name('patient.index');
Route::get('/patients/{patient}', [Doctor\DashboardController::class, 'showPatient'])->name('patient.show');

// Appointment session routes
Route::post('/appointments/{appointment}/start', [Doctor\DashboardController::class, 'startAppointment'])->name('appointments.start');
Route::post('/appointments/{appointment}/end', [Doctor\DashboardController::class, 'endAppointment'])->name('appointments.end');
Route::get('/appointments/{appointment}/details', [Doctor\DashboardController::class, 'showAppointmentDetails'])->name('appointments.details');
Route::post('/appointments/{appointment}/save-details', [Doctor\DashboardController::class, 'saveAppointmentDetails'])->name('appointments.save-details');
Route::get('/patients/{patient}/appointment-history', [Doctor\DashboardController::class, 'showAppointmentHistory'])->name('patients.appointment-history');
Route::get('/appointments/{appointment}/end', [Doctor\DashboardController::class, 'endAppointment'])->name('appointments.end.get');