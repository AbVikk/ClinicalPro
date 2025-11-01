<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Models\Appointment;
use App\Models\AppointmentDetail;
use App\Models\Vitals;
use App\Models\ClinicalNote;
use App\Models\Medication;
use App\Models\Prescription;
use App\Models\User;
use App\Models\Clinic; 
use App\Models\LabTest; // Import LabTest model
use App\Models\Consultation;
use App\Models\DoctorSchedule; 
use App\Models\LeaveRequest; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage; // Import Storage for file uploads

class DashboardController extends Controller
{
    // Helper method to create a notification
    private function createNotification($userId, $type, $message)
    {
        \App\Models\Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
            'is_read' => false,
            'channel' => 'database', // Default channel for in-app notifications
        ]);

        // --- FIX: Erase notification cache keys immediately after creation (The Whistleblower) ---
        Cache::forget("doctor_{$userId}_notifications");
        Cache::forget("doctor_{$userId}_notification_count");
        // We also clear the request count because the badge combines both
        Cache::forget("doctor_{$userId}_request_count");
        // --- END FIX ---
    }
    
    // Method to mark all notifications as read
    public function markNotificationsAsRead(Request $request)
    {
        Auth::user()->notifications()->update(['is_read' => true]);
        // When notifications are read, clear the notification cache keys
        Cache::forget("doctor_".Auth::id()."_notifications");
        Cache::forget("doctor_".Auth::id()."_notification_count");
        return response()->json(['success' => true]);
    }
    
    // Method to mark individual notification as read
    public function markNotificationAsRead(Request $request, $notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->update(['is_read' => true]);
            // Clear notification cache keys
            Cache::forget("doctor_".Auth::id()."_notifications");
            Cache::forget("doctor_".Auth::id()."_notification_count");
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }
    
    // Helper method to get unread notifications for the authenticated user
    private function getUnreadNotifications()
    {
        // This is the only place it is uncached for the initial page load
        return Auth::user()->notifications()
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }
    
    // Helper method to get notification count
    private function getNotificationCount()
    {
            return Auth::user()->notifications()
                ->where('is_read', false)
                ->count();
    }
    
    // Helper method to get appointment request count
    private function getAppointmentRequestCount()
    {
        $doctorId = Auth::id();
            return Appointment::where('doctor_id', $doctorId)
                ->whereIn('status', ['pending', 'new', 'scheduled'])
                ->count();
    }
    
    // --- FIX: Helper method to gather and save Vitals ---
    private function saveVitals(Request $request, $appointmentId, $doctorId) {
        $vitalsData = $request->only(['blood_pressure', 'temperature', 'pulse', 'respiratory_rate', 'spo2', 'height', 'weight', 'waist', 'bsa', 'bmi']);
        // Filter out null or empty values
        $vitalsData = array_filter($vitalsData, function($value) {
            return !is_null($value) && $value !== '';
        });

        if (!empty($vitalsData)) {
            $vitalsData['doctor_id'] = $doctorId; 
            Vitals::updateOrCreate(
                ['appointment_id' => $appointmentId],
                $vitalsData
            );
        }
    }

    // --- FIX: Helper method to gather and save Clinical Notes ---
    private function saveClinicalNotes(Request $request, $appointmentId, $doctorId) {
        $clinicalData = $request->only(['clinical_notes', 'skin_allergy']);
        
        if (isset($clinicalData['clinical_notes']) || isset($clinicalData['skin_allergy'])) {
             ClinicalNote::updateOrCreate(
                ['appointment_id' => $appointmentId],
                [
                    'doctor_id' => $doctorId, 
                    'note_text' => $clinicalData['clinical_notes'] ?? null,
                    'skin_allergy' => $clinicalData['skin_allergy'] ?? null,
                    'note_type' => 'clinical'
                ]
            );
        }
    }

    // --- FIX: Helper method to gather and save Medications ---
    private function saveMedications(Request $request, $appointmentId) {
        Medication::where('appointment_id', $appointmentId)->delete();
        
        if ($request->medications) {
            foreach ($request->medications as $index => $medicationData) {
                $medName = $medicationData['name'] ?? null; 

                if (empty($medName) && isset($medicationData['name_input'])) {
                    $medName = $medicationData['name_input'];
                }
                
                if (!empty($medName)) {
                    Medication::create([
                        'appointment_id' => $appointmentId,
                        'medication_name' => $medName,
                        'type' => $medicationData['type'] ?? null,
                        'dosage' => $medicationData['dosage'] ?? null,
                        'duration' => $medicationData['duration'] ?? null,
                        'instructions' => $medicationData['instructions'] ?? null,
                    ]);
                }
            }
        }
    }

    // --- FIX: Helper method to gather and save Lab Tests ---
    private function saveLabTests(Request $request, $appointmentId, $doctorId) {
        $labTests = $request->lab_tests ?? [];
        $existingTestNames = [];
        
        $allTests = LabTest::where('appointment_id', $appointmentId)->get()->keyBy('test_name');

        foreach ($labTests as $testData) {
            $testName = $testData['name'] ?? null;
            $filePath = $testData['file_path'] ?? null;

            if ($testName) {
                $test = $allTests->get($testName);

                if (isset($testData['file']) && $testData['file'] instanceof \Illuminate\Http\UploadedFile) {
                    $filePath = $testData['file']->store('lab_tests', 'public');
                }
                
                $test = LabTest::updateOrCreate(
                    ['appointment_id' => $appointmentId, 'test_name' => $testName],
                    [
                        'doctor_id' => $doctorId, 
                        'file_path' => $filePath
                    ]
                );

                $existingTestNames[] = $testName;
            }
        }
        
        LabTest::where('appointment_id', $appointmentId)
            ->whereNotIn('test_name', $existingTestNames)
            ->delete();
    }
    
    public function index()
    {
        // Get today's date
        $today = Carbon::today();
        
        // Get tomorrow's date
        $tomorrow = Carbon::tomorrow();
        
        // Get the authenticated doctor's ID
        $doctorId = Auth::user()->id;
        
        // --- NEW: Auto-Update Missed Appointments ---
        Appointment::where('doctor_id', $doctorId)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('appointment_time', '<', now())
            ->update(['status' => 'missed']);
        // --- END: Auto-Update Missed Appointments ---

        // Get notification/request counts (now from cache)
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();

        // Set a "cache time" in seconds. 3600 = 1 hour.
        $cacheTime = 3600; 
        
        // 1. Wrap the query in Cache::remember
        $todaysAppointments = Cache::remember("doctor_{$doctorId}_todays_appointments", $cacheTime, function () use ($doctorId, $today) {
            return Appointment::with(['patient', 'consultation'])
                ->where('doctor_id', $doctorId)
                ->whereDate('appointment_time', $today)
                ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
                ->orderBy('appointment_time')
                ->get();
        });
        
        $todaysAppointmentsCount = $todaysAppointments->count();

        $upcomingAppointments = Cache::remember("doctor_{$doctorId}_upcoming_appointments", $cacheTime, function () use ($doctorId, $tomorrow) {
            return Appointment::with(['patient', 'consultation'])
                ->where('doctor_id', $doctorId)
                ->where('appointment_time', '>=', $tomorrow)
                ->where('status', 'confirmed')
                ->orderBy('appointment_time')
                ->limit(5)
                ->get();
        });
        
        // --- DATA FOR 'TASKS' TAB ---
        $pendingTasks = Cache::remember("doctor_{$doctorId}_pending_tasks", $cacheTime, function () use ($doctorId) {
            return Appointment::with(['patient'])
                ->where('doctor_id', $doctorId)
                ->where('status', 'pending')
                ->orderBy('appointment_time')
                ->limit(5)
                ->get();
        });
        
        $recentPrescriptions = Cache::remember("doctor_{$doctorId}_recent_prescriptions", $cacheTime, function () use ($doctorId) {
            return Prescription::with(['patient', 'items.drug'])
                ->where('doctor_id', $doctorId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        });
        
        // --- DATA FOR 'STATS' TAB ---
        $currentYear = Carbon::now()->year;

        $patientVisits = Cache::remember("doctor_{$doctorId}_patient_visits_chart", $cacheTime, function () use ($doctorId, $currentYear) {
            $visits = [];
            for ($month = 1; $month <= 12; $month++) {
                $visits[] = Appointment::where('doctor_id', $doctorId)
                    ->whereYear('appointment_time', $currentYear)
                    ->whereMonth('appointment_time', $month)
                    ->where('status', 'completed')
                    ->count();
            }
            return $visits;
        });
        
        $totalYearlyVisits = array_sum($patientVisits);
        
        $avgDailyVisits = $totalYearlyVisits > 0 ? round($totalYearlyVisits / 365, 1) : 0;
        
        $lastMonth = Carbon::now()->subMonth();

        $lastMonthVisits = Cache::remember("doctor_{$doctorId}_last_month_visits", $cacheTime, function () use ($doctorId, $lastMonth) {
            return Appointment::where('doctor_id', $doctorId)
                ->whereYear('appointment_time', $lastMonth->year)
                ->whereMonth('appointment_time', $lastMonth->month)
                ->where('status', 'completed')
                ->count();
        });
        
        $monthlyChange = $patientVisits[Carbon::now()->month - 1] - $lastMonthVisits;
        
        $lastYearTotal = Cache::remember("doctor_{$doctorId}_last_year_total", $cacheTime, function () use ($doctorId, $currentYear) {
            return Appointment::where('doctor_id', $doctorId)
                ->whereYear('appointment_time', $currentYear - 1)
                ->where('status', 'completed')
                ->count();
        });
        
        $yearlyTrend = $lastYearTotal > 0 ? round((($totalYearlyVisits - $lastYearTotal) / $lastYearTotal) * 100, 1) : 0;
        
        // Simulated data should NOT be cached, as it will just show the same "random" number.
        $satisfactionData = []; // Simulated
        for ($month = 1; $month <= 12; $month++) { $satisfactionData[] = rand(35, 50) / 10; }
        $currentRating = end($satisfactionData);
        $previousRating = $satisfactionData[count($satisfactionData) - 2] ?? $currentRating;
        $ratingChange = round($currentRating - $previousRating, 1);
        $totalReviews = rand(1000, 1500);
        $reviewsChange = rand(100, 200);
        $recommendationPercentage = rand(85, 95);

        // --- DATA FOR NEW WIDGETS & CARDS ---
        $totalAppointmentsCount = Cache::remember("doctor_{$doctorId}_total_appointments", $cacheTime, function () use ($doctorId) {
            return Appointment::where('doctor_id', $doctorId)->count();
        });
        
        $onlineConsultationsCount = Cache::remember("doctor_{$doctorId}_online_consultations", $cacheTime, function () use ($doctorId) {
            return Consultation::where('doctor_id', $doctorId)
                ->where('delivery_channel', 'virtual')
                ->count();
        });

        $cancelledAppointmentsCount = Cache::remember("doctor_{$doctorId}_cancelled_appointments", $cacheTime, function () use ($doctorId) {
            return Appointment::where('doctor_id', $doctorId)
                ->where('status', 'cancelled')
                ->count();
        });

        $totalPatientsCount = Cache::remember("doctor_{$doctorId}_total_patients", $cacheTime, function () use ($doctorId) {
            return Appointment::where('doctor_id', $doctorId)
                ->where('status', 'completed')
                ->distinct('patient_id')
                ->count('patient_id');
        });

        $videoConsultationsCount = $onlineConsultationsCount; // Re-using variable
        
        // Placeholders for stats you may not be tracking yet
        $rescheduledCount = 0; 
        $preVisitBookingsCount = 0; 
        $walkinBookingsCount = 0; 

        $followUpsCount = Cache::remember("doctor_{$doctorId}_follow_ups", $cacheTime, function () use ($doctorId) {
            return AppointmentDetail::whereHas('appointment', function($q) use ($doctorId){
                    $q->where('doctor_id', $doctorId);
                })
                ->whereNotNull('follow_up_date')
                ->count();
        });
        
        // This is the data for the "My Availability" card
        $doctorSchedule = Cache::remember("doctor_{$doctorId}_schedule", $cacheTime, function () use ($doctorId) {
            return DoctorSchedule::where('doctor_id', $doctorId)
                ->with('clinic') // <-- Eager load the clinic name
                ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
                ->get()
                ->groupBy('day_of_week'); // Group by day for easy display
        });

        // This is the data for the "Top Patients" card
        $topPatients = Cache::remember("doctor_{$doctorId}_top_patients", $cacheTime, function () use ($doctorId) {
            return User::where('role', 'patient')
                ->whereHas('appointmentsAsPatient', function ($query) use ($doctorId) {
                    $query->where('doctor_id', $doctorId)->where('status', 'completed');
                })
                ->withCount(['appointmentsAsPatient as appointments_as_patient_count' => function ($query) use ($doctorId) {
                    $query->where('doctor_id', $doctorId)->where('status', 'completed');
                }])
                ->orderBy('appointments_as_patient_count', 'desc')
                ->limit(5)
                ->get();
        });
        
        // --- Return ALL variables to the view ---
        return view('doctor.dashboard', compact(
            // Original Tab Data
            'notifications', 'notificationCount', 'requestCount',
            'todaysAppointmentsCount', 'todaysAppointments', 'upcomingAppointments',
            'pendingTasks', 'recentPrescriptions',
            'patientVisits', 'avgDailyVisits', 'totalYearlyVisits', 'monthlyChange', 'yearlyTrend',
            'satisfactionData', 'currentRating', 'ratingChange', 'totalReviews', 'reviewsChange', 'recommendationPercentage',
            
            // New Widgets & Cards Data
            'totalAppointmentsCount', 'onlineConsultationsCount', 'cancelledAppointmentsCount',
            'totalPatientsCount', 'videoConsultationsCount', 'rescheduledCount', 
            'preVisitBookingsCount', 'walkinBookingsCount', 'followUpsCount',
            'doctorSchedule', 'topPatients'
        ));
    }

    /**
     * Display the requests page for the doctor
     */
    public function requests()
    {
        // Get the authenticated doctor's ID
        $doctorId = Auth::user()->id;
        
        // Get unread notifications
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        
        // Get all new appointments for this doctor (status = 'pending' for new requests)
        // This page is for *live* requests, so we probably should NOT cache it.
        $requests = Appointment::with(['patient.patient', 'appointmentReason'])
            ->where('doctor_id', $doctorId)
            ->where('status', 'pending') // Changed from 'confirmed' to 'pending'
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('doctor.requests', compact('requests', 'notifications', 'notificationCount', 'requestCount'));
    }

    public function appointments(Request $request)
    {
        // Get the authenticated doctor's ID
        $doctorId = Auth::user()->id;
        
        // Get unread notifications
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        
        // Get filter parameters
        $search = $request->get('search');
        $filter = $request->get('filter', 'all'); // all, chat, direct
        $tab = $request->get('tab', 'upcoming'); // upcoming, inprogress, cancelled, completed, missed
        
        // As we discussed, we will NOT cache this simple list page.
        // Caching paginated lists is complex and this page is likely fast enough.

        // Check if this is an AJAX request for a specific tab
        if ($request->ajax() || $request->get('ajax')) {
            // We'll use getAppointmentsForTab, which we will also update
            return $this->getAppointmentsForTab($doctorId, $tab, $search, $filter);
        }
        
        // Build the base query for appointments
        $baseQuery = Appointment::with(['patient', 'appointmentReason', 'consultation']) // Eager load patient
            ->where('doctor_id', $doctorId);
            
        // Apply search filter (keep existing)
        if ($search) {
            $baseQuery->where(function($q) use ($search) {
                $q->whereHas('patient', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        
        // Apply type filter (keep existing)
        if ($filter !== 'all') {
            $baseQuery->where('type', $filter);
        }
        
        // Clone the base query for each tab
        $upcomingQuery = clone $baseQuery;
        $inProgressQuery = clone $baseQuery;
        $cancelledQuery = clone $baseQuery;
        $completedQuery = clone $baseQuery;
        $missedQuery = clone $baseQuery; // <-- ADDED
        
        // Apply status filters for each tab
        $upcomingQuery->where('status', 'confirmed')
                      ->where('appointment_time', '>', now());
        
        $inProgressQuery->where('status', 'in_progress');
        $cancelledQuery->where('status', 'cancelled');
        $completedQuery->where('status', 'completed');
        $missedQuery->where('status', 'missed'); // <-- ADDED
        
        // Get paginated results for the active tab
        $perPage = 10;
        switch ($tab) {
            case 'inprogress':
                $appointments = $inProgressQuery->orderBy('appointment_time', 'asc')->paginate($perPage)->appends(request()->query());
                break;
            case 'cancelled':
                $appointments = $cancelledQuery->orderBy('appointment_time', 'desc')->paginate($perPage)->appends(request()->query());
                break;
            case 'completed':
                $appointments = $completedQuery->orderBy('appointment_time', 'desc')->paginate($perPage)->appends(request()->query());
                break;
            case 'missed': // <-- ADDED
                $appointments = $missedQuery->orderBy('appointment_time', 'desc')->paginate($perPage)->appends(request()->query());
                break;
            case 'upcoming':
            default:
                $appointments = $upcomingQuery->orderBy('appointment_time', 'asc')->paginate($perPage)->appends(request()->query());
                break;
        }
        
        // Get counts for all tabs (without pagination)
        $upcomingCount = $upcomingQuery->count();
        $inProgressCount = $inProgressQuery->count();
        $cancelledCount = $cancelledQuery->count();
        $completedCount = $completedQuery->count();
        $missedCount = $missedQuery->count(); // <-- ADDED
        
        return view('doctor.appointments', compact(
            'appointments',
            'upcomingCount', 
            'inProgressCount',
            'cancelledCount', 
            'completedCount',
            'missedCount', // <-- ADDED
            'search',
            'filter',
            'tab',
            'notifications',
            'notificationCount',
            'requestCount'
        ));
    }
    
    private function getAppointmentsForTab($doctorId, $tab, $search, $filter)
    {
        // Build the base query for appointments
        $baseQuery = Appointment::with(['patient', 'appointmentReason', 'consultation']) // Eager load patient
            ->where('doctor_id', $doctorId);
            
        // Apply search filter (keep existing)
        if ($search) {
            $baseQuery->where(function($q) use ($search) {
                $q->whereHas('patient', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        
        // Apply type filter (keep existing)
        if ($filter !== 'all' && $filter) {
            $baseQuery->where('type', $filter);
        }
        
        // Apply status filter based on tab
        switch ($tab) {
            case 'inprogress':
                $baseQuery->where('status', 'in_progress');
                break;
            case 'cancelled':
                $baseQuery->where('status', 'cancelled');
                break;
            case 'completed':
                $baseQuery->where('status', 'completed');
                break;
            case 'missed': // <-- ADDED
                $baseQuery->where('status', 'missed');
                break;
            case 'upcoming':
            default:
                $baseQuery->where('status', 'confirmed')
                          ->where('appointment_time', '>', now());
                break;
        }
        
        // Get appointments based on tab
        $perPage = 10;
        switch ($tab) {
            case 'inprogress':
                $appointments = $baseQuery->orderBy('appointment_time', 'asc')->paginate($perPage);
                break;
            case 'cancelled':
            case 'completed':
            case 'missed': // <-- ADDED
                $appointments = $baseQuery->orderBy('appointment_time', 'desc')->paginate($perPage);
                break;
            case 'upcoming':
            default:
                $appointments = $baseQuery->orderBy('appointment_time', 'asc')->paginate($perPage);
                break;
        }
        
        // Return JSON response with rendered view
        $view = view('doctor.appointments-tab-content', compact('appointments', 'tab'))->render();
        
        return response()->json([
            'success' => true,
            'view' => $view,
            'tab' => $tab
        ]);
    }

    /**
     * Accept an appointment request
     */
    public function acceptRequest(Request $request, $appointmentId)
    {
        // Find the appointment by ID
        $appointment = Appointment::find($appointmentId);
        
        // If appointment not found, return error
        if (!$appointment) {
            return response()->json(['success' => false, 'message' => 'Appointment not found'], 404);
        }
        
        // Verify the appointment belongs to the authenticated doctor
        if ($appointment->doctor_id !== Auth::user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        try {
            // Determine the appointment time
            $appointmentTime = $request->appointment_time ?? $appointment->appointment_time;
            
            // If the appointment time is in the past, set it to 1 hour from now
            if (strtotime($appointmentTime) <= time()) {
                $appointmentTime = now()->addHour();
            }
            
            // Update the appointment status to 'confirmed'
            $appointment->update([
                'status' => 'confirmed',
                'appointment_time' => $appointmentTime
            ]);

            $doctorId = Auth::user()->id;
            Cache::forget("doctor_{$doctorId}_todays_appointments");
            Cache::forget("doctor_{$doctorId}_request_count");
            Cache::forget("doctor_{$doctorId}_pending_tasks");
            Cache::forget("doctor_{$doctorId}_upcoming_appointments");
            
            return response()->json([
                'success' => true,
                'message' => 'Appointment accepted successfully',
                'appointment_time' => $appointment->appointment_time
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error accepting appointment: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Reject an appointment request
     */
    public function rejectRequest(Request $request, Appointment $appointment)
    {
        // Verify the appointment belongs to the authenticated doctor
        if ($appointment->doctor_id !== Auth::user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        try {
            // Update the appointment status to 'cancelled'
            $appointment->update([
                'status' => 'cancelled',
                'cancel_reason' => $request->cancel_reason,
                'cancel_type' => $request->cancel_type
            ]);

            // Erase the answers that just changed
            $doctorId = Auth::user()->id;
            Cache::forget("doctor_{$doctorId}_todays_appointments");
            Cache::forget("doctor_{$doctorId}_cancelled_appointments");
            Cache::forget("doctor_{$doctorId}_request_count");
            Cache::forget("doctor_{$doctorId}_pending_tasks");
            
            return response()->json([
                'success' => true,
                'message' => 'Appointment rejected successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error rejecting appointment: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Start an appointment session
     */
    public function startAppointment(Request $request, Appointment $appointment)
    {
        // Verify the appointment belongs to the authenticated doctor
        if ($appointment->doctor_id !== Auth::user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        try {
            // Update the appointment status to 'in_progress'
            $appointment->update([
                'status' => 'in_progress',
                'started_at' => now()
            ]);
            
            // Create appointment details record if it doesn't exist
            AppointmentDetail::firstOrCreate([
                'appointment_id' => $appointment->id
            ]);

            // --- THIS IS THE NEW PART (The "Walkie-Talkie") ---
            $doctorId = Auth::user()->id;
            Cache::forget("doctor_{$doctorId}_todays_appointments");
            Cache::forget("doctor_{$doctorId}_upcoming_appointments"); // It's no longer "upcoming"
            
            return response()->json([
                'success' => true,
                'message' => 'Appointment started successfully',
                'status' => 'in_progress',
                'redirect_url' => route('doctor.appointments.details', $appointment->id)
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error starting appointment: ' . $e->getMessage()], 500);
        }
    }

    /**
     * End an appointment session
     */
    public function endAppointment(Request $request, Appointment $appointment)
    {
        // Verify the appointment belongs to the authenticated doctor
        if ($appointment->doctor_id !== Auth::user()->id) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            return redirect()->route('doctor.appointments')->with('error', 'Unauthorized access');
        }
        
        // Handle GET request - show a confirmation page
        if ($request->isMethod('get')) {
            // Get unread notifications
            $notifications = $this->getUnreadNotifications();
            $notificationCount = $this->getNotificationCount();
            $requestCount = $this->getAppointmentRequestCount();
            
            // For direct access via GET, show confirmation page
            return view('doctor.confirm-end-appointment', compact('appointment', 'notifications', 'notificationCount', 'requestCount'));
        }
        
        // Handle POST request (AJAX or form submission)
        // Validate the request
        $request->validate([
            'end_reason' => 'required|string|max:500'
        ]);
        
        try {
            // Update the appointment status to 'completed'
            $appointment->update([
                'status' => 'completed',
                'completed_at' => now(),
                'end_reason' => $request->end_reason
            ]);
            
            // Update the consultation end_time if there's a linked consultation
            $payment = $appointment->payment;
            if ($payment && $payment->consultation_id) {
                $consultation = Consultation::find($payment->consultation_id);
                if ($consultation) {
                    $consultation->update([
                        'end_time' => now(),
                        'status' => 'completed'
                    ]);
                }
            }

            // Erase ALL the answers from the "whiteboard"
            $doctorId = Auth::user()->id;
            // Clear all dashboard-related cache keys for this doctor
            $cacheKeys = [
                "doctor_{$doctorId}_todays_appointments",
                "doctor_{$doctorId}_upcoming_appointments",
                "doctor_{$doctorId}_pending_tasks",
                "doctor_{$doctorId}_recent_prescriptions",
                "doctor_{$doctorId}_patient_visits_chart",
                "doctor_{$doctorId}_last_month_visits",
                "doctor_{$doctorId}_last_year_total",
                "doctor_{$doctorId}_total_appointments",
                "doctor_{$doctorId}_online_consultations",
                "doctor_{$doctorId}_cancelled_appointments",
                "doctor_{$doctorId}_total_patients",
                "doctor_{$doctorId}_follow_ups",
                "doctor_{$doctorId}_schedule",
                "doctor_{$doctorId}_top_patients",
                "doctor_{$doctorId}_request_count"
            ];
            
            // --- FIX FOR forgetMultiple (Cache Erase) ---
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            // --- END OF FIX ---
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Appointment completed successfully',
                    'status' => 'completed'
                ]);
            }
            
            // For non-AJAX requests, redirect to dashboard with success message
            return redirect()->route('doctor.dashboard')->with('success', 'Appointment completed successfully');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error completing appointment: ' . $e->getMessage()], 500);
            }
            
            // For non-AJAX requests, redirect back with error message
            return redirect()->back()->with('error', 'Error completing appointment: ' . $e->getMessage());
        }
    }

    /**
     * Get the count of new requests for the authenticated doctor
     */
    public function getRequestCount()
    {
        // This is a helper, but we'll cache the count inside the helper itself
        $count = $this->getAppointmentRequestCount() + $this->getNotificationCount();
        return response()->json(['count' => $count]);
    }
    
    /**
     * Display the specified patient profile for doctors.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPatient($id)
    {
        // This is a "read" page, but it's specific to one patient.
        // It's probably fast enough, so we won't cache it for now.
        // Get the authenticated doctor's ID
        $doctorId = Auth::user()->id;
        
        // Get unread notifications
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        
        // Find the patient and verify they have appointments with this doctor
        $patient = User::where('role', 'patient')
            ->whereHas('appointmentsAsPatient', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->findOrFail($id);
            
        // Get recent appointments for this patient with this doctor (limit 3)
        $recentAppointments = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', $doctorId)
            ->with(['appointmentReason'])
            ->orderBy('appointment_time', 'desc')
            ->limit(3)
            ->get();
            
        // Get all appointments for this patient with this doctor
        $allAppointments = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', $doctorId)
            ->with(['appointmentReason'])
            ->orderBy('appointment_time', 'desc')
            ->get();
            
        // Get next upcoming appointment (future appointments only)
        $nextAppointment = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', $doctorId)
            ->where('status', 'confirmed')
            ->where('appointment_time', '>=', now())
            ->with(['appointmentReason'])
            ->orderBy('appointment_time', 'asc')
            ->first();
            
        // Get prescriptions for this patient created by this doctor
        $patientPrescriptions = Prescription::where('patient_id', $patient->id)
            ->where('doctor_id', $doctorId)
            ->with(['items.drug'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('doctor.patient-profile', compact('patient', 'recentAppointments', 'allAppointments', 'patientPrescriptions', 'nextAppointment', 'notifications', 'notificationCount', 'requestCount'));
    }
    
    /**
     * Display a list of patients for the authenticated doctor.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPatient()
    {
        // This is a simple list page, we will not cache it.
        // Get the authenticated doctor's ID
        $doctorId = Auth::user()->id;
        
        // Get unread notifications
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        
        // Get all patients who have appointments with this doctor
        $patients = User::where('role', 'patient')
            ->whereHas('appointmentsAsPatient', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->with(['appointmentsAsPatient' => function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId)
                    ->orderBy('appointment_time', 'desc');
            }])
            ->paginate(20);
        
        return view('doctor.patients', compact('patients', 'notifications', 'notificationCount', 'requestCount'));
    }

    /**
     * Display the appointment details page
     */
    public function showAppointmentDetails(Appointment $appointment)
    {
        // This is a "live" editing page. We must NOT cache this.
        // Verify the appointment belongs to the authenticated doctor
        if ($appointment->doctor_id !== Auth::user()->id) {
            return redirect()->route('doctor.appointments')->with('error', 'Unauthorized access');
        }
        
        // Verify the appointment is in progress
        if ($appointment->status !== 'in_progress') {
            return redirect()->route('doctor.appointments')->with('error', 'Appointment is not in progress');
        }
        
        // Get unread notifications
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        
        // Get or create appointment details
        $appointmentDetail = AppointmentDetail::firstOrCreate([
            'appointment_id' => $appointment->id
        ]);
        
        // Load related data through the appointment
        $appointment->load(['vitals', 'clinicalNote', 'medications', 'labTests', 'doctor', 'patient', 'appointmentReason', 'appointmentDetail', 'consultation.clinic']);
        
        // Get drug data for medication selection
        $drugs = \App\Models\Drug::orderBy('name')->get();
        $categories = \App\Models\DrugCategory::orderBy('name')->get();
        $dosages = \App\Models\DrugMg::orderBy('mg_value')->get();
        
        // Calculate number of visits for this patient with this doctor
        $noOfVisits = \App\Models\Appointment::where('patient_id', $appointment->patient_id)
            ->where('doctor_id', $appointment->doctor_id)
            ->where('status', 'completed')
            ->count();
        
        return view('doctor.appointment-details', compact('appointment', 'appointmentDetail', 'drugs', 'categories', 'dosages', 'notifications', 'notificationCount', 'requestCount', 'noOfVisits'));
    }
    
    /**
     * Save appointment details
     */
    public function saveAppointmentDetails(Request $request, Appointment $appointment)
    {
        // Verify the appointment belongs to the authenticated doctor
        $doctorId = Auth::user()->id; // FIX: Define doctorId here
        
        if ($appointment->doctor_id !== $doctorId) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
            }
            return redirect()->route('doctor.appointments')->with('error', 'Unauthorized access');
        }
        
        // Check if this is a partial update (section-specific save)
        $isPartialUpdate = (
                          $request->has('complaints') || 
                          $request->has('diagnosis') || 
                          $request->has('medications') || 
                          $request->has('lab_tests') || 
                          $request->has('blood_group') ||
                          $request->has('advice') ||
                          $request->has('follow_up_date') ||
                          $request->has('follow_up_time') ||
                          $request->has('clinical_notes') ||
                          $request->has('skin_allergy') ||
                          $request->hasAny(['blood_pressure', 'temperature', 'pulse', 'respiratory_rate', 'spo2', 'height', 'weight', 'waist', 'bsa', 'bmi'])
        ) && !$request->has('is_full_update'); 

        // FIX: Re-run validation as full validation might be too strict for partial updates
        $validatorRules = [
            'blood_group' => 'nullable|string|max:10',
            'advice' => 'nullable|string|max:1000',
            'follow_up_date' => 'nullable|date',
            'follow_up_time' => 'nullable|date_format:H:i',
            // Vitals validation
            'blood_pressure' => 'nullable|string|max:20',
            'temperature' => 'nullable|string|max:10',
            'pulse' => 'nullable|string|max:10',
            'respiratory_rate' => 'nullable|string|max:10',
            'spo2' => 'nullable|string|max:10',
            'height' => 'nullable|string|max:10',
            'weight' => 'nullable|string|max:10',
            'waist' => 'nullable|string|max:10',
            'bsa' => 'nullable|string|max:10',
            'bmi' => 'nullable|string|max:10',
            // Clinical notes
            'clinical_notes' => 'nullable|string|max:1000',
            'skin_allergy' => 'nullable|string|max:500',
            // Medications validation (using basic checks for partial save)
            'medications' => 'nullable|array',
            'medications.*.name' => 'nullable|string|max:100',
            'medications.*.type' => 'nullable|string|max:50',
            'medications.*.dosage' => 'nullable|string|max:50',
            'medications.*.duration' => 'nullable|string|max:50',
            'medications.*.instructions' => 'nullable|string|max:200',
            // Lab tests validation (using basic checks for partial save)
            'lab_tests' => 'nullable|array',
            // FIX: Allow file upload per item
            'lab_tests.*.file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif,txt|max:2048', 
            'lab_tests.*.name' => 'nullable|string|max:100',
            // Complaints and diagnosis validation
            'complaints' => 'nullable|array',
            'complaints.*' => 'nullable|string|max:200',
            'diagnosis' => 'nullable|array',
            'diagnosis.*' => 'nullable|string|max:200',
        ];
        
        $validator = Validator::make($request->all(), $validatorRules);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Validation failed: ' . implode(', ', $validator->errors()->all())], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            // Get or create appointment details
            $appointmentDetail = AppointmentDetail::firstOrCreate([
                'appointment_id' => $appointment->id
            ]);
            
            // --- PARTIAL UPDATE LOGIC ---
            if ($isPartialUpdate) {
                
                // 1. Save Vitals
                $this->saveVitals($request, $appointment->id, $doctorId);

                // 2. Save Clinical Notes (Skin Allergy/Notes)
                $this->saveClinicalNotes($request, $appointment->id, $doctorId);

                // 3. Save Medications
                if ($request->has('medications')) {
                    $this->saveMedications($request, $appointment->id);
                    Cache::forget("doctor_".Auth::id()."_recent_prescriptions");
                }

                // 4. Save Lab Tests
                if ($request->has('lab_tests')) {
                    $this->saveLabTests($request, $appointment->id, $doctorId);
                }

                // 5. Save AppointmentDetail fields (Advice, Blood Group, Complaints, Diagnosis, Follow-up)
                $updateDetailData = [];
                if ($request->has('blood_group')) $updateDetailData['blood_group'] = $request->blood_group;
                if ($request->has('advice')) $updateDetailData['advice'] = $request->advice;
                if ($request->has('follow_up_date')) $updateDetailData['follow_up_date'] = $request->follow_up_date;
                if ($request->has('follow_up_time')) $updateDetailData['follow_up_time'] = $request->follow_up_time;
                if ($request->has('complaints')) $updateDetailData['complaints'] = $request->complaints;
                if ($request->has('diagnosis')) $updateDetailData['diagnosis'] = $request->diagnosis;
                
                if (!empty($updateDetailData)) {
                    $appointmentDetail->update($updateDetailData);
                }

                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Section data saved successfully.'
                    ]);
                }
                
                return redirect()->back()->with('success', 'Section data saved successfully.');
            }
            
            // --- FULL UPDATE LOGIC (Save & End) ---
            
            // 1. Save Vitals
            $this->saveVitals($request, $appointment->id, $doctorId);

            // 2. Save Clinical Notes (Skin Allergy/Notes)
            $this->saveClinicalNotes($request, $appointment->id, $doctorId);
            
            // 3. Save Medications
            $this->saveMedications($request, $appointment->id);
            
            // 4. Save Lab Tests
            $this->saveLabTests($request, $appointment->id, $doctorId);

            // 5. Save AppointmentDetail fields (All fields)
            $appointmentDetail->update([
                'blood_group' => $request->blood_group,
                'advice' => $request->advice,
                'follow_up_date' => $request->follow_up_date,
                'follow_up_time' => $request->follow_up_time,
                'complaints' => $request->complaints,
                'diagnosis' => $request->diagnosis,
            ]);

            // 6. Update the appointment status to 'completed'
            $appointment->update([
                'status' => 'completed',
                'completed_at' => now(),
                'end_reason' => 'Session completed by doctor'
            ]);
            
            // 7. Update the consultation end_time
            $payment = $appointment->payment;
            if ($payment && $payment->consultation_id) {
                $consultation = Consultation::find($payment->consultation_id);
                if ($consultation) {
                    $consultation->update([
                        'end_time' => now(),
                        'status' => 'completed'
                    ]);
                }
            }
            
            // 8. Clear Cache (FIXED loop)
            $cacheKeys = [
                "doctor_{$doctorId}_todays_appointments",
                "doctor_{$doctorId}_upcoming_appointments",
                "doctor_{$doctorId}_pending_tasks",
                "doctor_{$doctorId}_recent_prescriptions",
                "doctor_{$doctorId}_patient_visits_chart",
                "doctor_{$doctorId}_last_month_visits",
                "doctor_{$doctorId}_last_year_total",
                "doctor_{$doctorId}_total_appointments",
                "doctor_{$doctorId}_online_consultations",
                "doctor_{$doctorId}_cancelled_appointments",
                "doctor_{$doctorId}_total_patients",
                "doctor_{$doctorId}_follow_ups",
                "doctor_{$doctorId}_schedule",
                "doctor_{$doctorId}_top_patients",
                "doctor_{$doctorId}_request_count"
            ];
            
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Appointment details saved successfully and session completed.',
                    'redirect_url' => route('doctor.dashboard')
                ]);
            }
            
            return redirect()->route('doctor.dashboard')->with('success', 'Appointment details saved successfully and session completed.');
        } catch (\Exception $e) {
            // FIX: Log and return the exception message
            Log::error('Error saving appointment details: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error saving appointment details: ' . $e->getMessage()], 500);
            }
            
            return redirect()->back()->with('error', 'Error saving appointment details: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Display appointment history for a patient
     */
    public function showAppointmentHistory($patientId)
    {
        // This is a "read" page, but it's specific to one patient.
        // It's probably fast enough, so we won't cache it for now.
        // Get the authenticated doctor's ID
        $doctorId = Auth::user()->id;

        // Get unread notifications
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();

        // Find the patient
        $patient = User::where('role', 'patient')
            ->whereHas('appointmentsAsPatient', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->findOrFail($patientId);

        // --- THIS IS THE EFFICIENT QUERY ---
        // Get all completed appointments and EAGER LOAD (with)
        // all relationships we need in one go.
        $completedAppointments = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->with([
                'doctor', 
                'patient', 
                'appointmentReason', 
                'vitals', 
                'clinicalNote', 
                'medications', 
                'labTests', 
                'appointmentDetail', // This will automatically get the related AppointmentDetail
                'consultation.clinic' // This loads the consultation AND its clinic
            ])
            ->orderBy('appointment_time', 'desc')
            ->get();
        // --- END OF EFFICIENT QUERY ---
            
        // Get total visit count (this is fast)
        $totalVisits = $completedAppointments->count();
            
        // If no completed appointments, redirect back
        if ($completedAppointments->isEmpty()) {
            return redirect()->back()->with('error', 'No completed appointments found for this patient.');
        }
        
        // Get the most recent completed appointment as the current one
        $currentAppointment = $completedAppointments->first();
        
        // Get appointment detail for the current appointment
        // We can just get it from the loaded relationship
        $currentAppointmentDetail = $currentAppointment->appointmentDetail ?? new AppointmentDetail();
        
        // Prepare appointments data for JavaScript
        $appointmentsData = [];
        // --- THIS LOOP IS NOW FAST ---
        // All data is already loaded, we are just organizing it.
        foreach ($completedAppointments as $appointment) {
            
            // We can just use the eager-loaded data
            $appointmentDetail = $appointment->appointmentDetail ?? new AppointmentDetail();

            // Prepare lab tests data
            $labTestsData = [];
            if ($appointment->labTests) {
                foreach ($appointment->labTests as $labTest) {
                    $labTestsData[] = [
                        'id' => $labTest->id,
                        'name' => $labTest->test_name,
                        'file_path' => $labTest->file_path
                    ];
                }
            }
            
            // --- NEW DISPLAY LOGIC ---
            $typeDisplay = 'General Visit';
            $locationDisplay = $appointment->location ?? 'N/A';
            $clinicDisplay = $appointment->clinic_location ?? 'N/A';
            $serviceDisplay = $appointment->appointmentReason?->name ?? $appointment->type ?? 'General Visit';
            $durationDisplay = 30; // Default

            if ($appointment->consultation) {
                $typeDisplay = $appointment->consultation->delivery_channel == 'virtual' ? 'Virtual' : 'Physical';
                if ($appointment->consultation->delivery_channel == 'virtual') {
                    $locationDisplay = 'N/A';
                    $clinicDisplay = 'Virtual Session';
                } else {
                    $locationDisplay = $appointment->consultation->clinic?->address ?? 'N/A';
                    $clinicDisplay = $appointment->consultation->clinic?->name ?? 'N/A';
                }
                $serviceDisplay = $appointment->consultation->service_type;
                $durationDisplay = $appointment->consultation->duration_minutes;
            }
            // --- END NEW DISPLAY LOGIC ---

            $appointmentsData[] = [
                'id' => $appointment->id,
                'patient_name' => $appointment->patient?->name ?? 'Unknown Patient',
                'patient_email' => $appointment->patient?->email ?? '',
                'patient_phone' => $appointment->patient?->phone ?? '',
                'doctor_name' => $appointment->doctor?->name ?? 'Unknown Doctor',
                
                // Updated fields:
                'type_display' => $typeDisplay,
                'clinic_display' => $clinicDisplay,
                'location_display' => $locationDisplay,
                'service_display' => $serviceDisplay,
                'duration_display' => $durationDisplay,
                
                // Original fields:
                'status' => $appointment->status,
                'consultation_fee' => $appointment->consultation?->fee ?? $appointment->consultation_fee ?? 'N/A',
                'appointment_time' => $appointment->appointment_time->toIso8601String(), // Send as ISO string for JS
                'blood_group' => $appointmentDetail->blood_group,
                'clinical_notes' => $appointment->clinicalNote?->note_text ?? '',
                'skin_allergy' => $appointment->clinicalNote?->skin_allergy ?? '',
                'advice' => $appointmentDetail->advice,
                'follow_up_date' => $appointmentDetail->follow_up_date ? Carbon::parse($appointmentDetail->follow_up_date)->format('Y-m-d') : null,
                'follow_up_time' => $appointmentDetail->follow_up_time, // Send raw time
                
                // Vitals
                'blood_pressure' => $appointment->vitals?->blood_pressure ?? '',
                'temperature' => $appointment->vitals?->temperature ?? '',
                'pulse' => $appointment->vitals?->pulse ?? '',
                'respiratory_rate' => $appointment->vitals?->respiratory_rate ?? '',
                'spo2' => $appointment->vitals?->spo2 ?? '',
                'height' => $appointment->vitals?->height ?? '',
                'weight' => $appointment->vitals?->weight ?? '',
                'waist' => $appointment->vitals?->waist ?? '',
                'bsa' => $appointment->vitals?->bsa ?? '',
                'bmi' => $appointment->vitals?->bmi ?? '',
                
                // Tags
                'complaints' => $appointmentDetail->complaints ?? [],
                'diagnosis' => $appointmentDetail->diagnosis ?? [],
                
                // Lab Tests & Medications
                'lab_tests' => $labTestsData,
                'medications' => $appointment->medications->map(function($med) {
                    return [
                        'medication_name' => $med->medication_name,
                        'type' => $med->type,
                        'dosage' => $med->dosage,
                        'duration' => $med->duration,
                        'instructions' => $med->instructions
                    ];
                })->toArray()
            ];
        }
        // --- END OF FAST LOOP ---
        
        return view('doctor.appointment-history', compact(
            'patient', 
            'completedAppointments', 
            'currentAppointment', 
            'currentAppointmentDetail', 
            'totalVisits',
            'notifications', 
            'notificationCount', 
            'requestCount',
            'appointmentsData'
        ));
    }

    /**
     * Check doctor's availability for a follow-up appointment time via AJAX.
     */
    public function checkFollowUpAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'follow_up_date' => 'required|date_format:Y-m-d',
            'follow_up_time' => 'required|date_format:H:i',
            // We might need patient_id if rules depend on patient type, but not for basic check
        ]);

        if ($validator->fails()) {
            return response()->json(['available' => false, 'message' => $validator->errors()->first()], 422);
        }

        $doctorId = Auth::id();
        $followUpDate = $request->input('follow_up_date');
        $followUpTime = $request->input('follow_up_time') . ':00'; // Add seconds for comparison

        try {
            $selectedDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $followUpDate . ' ' . $followUpTime);
        } catch (\Exception $e) {
            return response()->json(['available' => false, 'message' => 'Invalid date/time format.'], 400);
        }

        $dayOfWeek = strtolower($selectedDateTime->format('l'));
        $time = $selectedDateTime->format('H:i:s');
        $date = $selectedDateTime->format('Y-m-d');

        // 1. Check Doctor's Schedule
        $isScheduled = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where(DB::raw('CAST(start_time AS TIME)'), '<=', $time)
            ->where(DB::raw('CAST(end_time AS TIME)'), '>', $time) // Check if START time is within schedule
            ->exists();

        if (!$isScheduled) {
            return response()->json(['available' => false, 'message' => 'Doctor is not scheduled to work at this time.']);
        }

        // 2. Check for Conflicting Consultations (assuming ~30 min duration for follow-up)
        // You might want to pass the expected duration from the frontend if it varies
        $assumedDuration = 30;
        $followUpEnd = $selectedDateTime->copy()->addMinutes($assumedDuration);

        $hasConflict = Consultation::where('doctor_id', $doctorId)
            ->whereNotIn('status', ['completed', 'missed', 'cancelled'])
            ->where(function ($query) use ($selectedDateTime, $followUpEnd) {
                $query->where('start_time', '<', $followUpEnd) // Existing starts before follow-up ends
                      ->where(DB::raw('DATE_ADD(start_time, INTERVAL duration_minutes MINUTE)'), '>', $selectedDateTime); // Existing ends after follow-up starts
            })
            ->exists();

        if ($hasConflict) {
            return response()->json(['available' => false, 'message' => 'Doctor has a conflicting appointment at this time.']);
        }

        // If no schedule conflict and no appointment conflict
        return response()->json(['available' => true, 'message' => 'Time slot appears available.']);
    }
    
    /**
     * Display the doctor's schedule management page
     */
    public function schedule()
    {
        // This is a "write" page (a form), so we will not cache it.
        // Get the authenticated doctor's ID
        $doctorId = Auth::user()->id;

        // Get unread notifications
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();

        // Get all physical clinics (still needed for the HTML dropdown)
        $clinics = \App\Models\Clinic::where('is_physical', 1)->get();

        // --- NEW: Prepare the list SPECIFICALLY for JavaScript ---
        $clinicsForJs = [];
        // Add the 'virtual' option first
        $clinicsForJs[] = ['id' => 'virtual', 'name' => 'Virtual Session'];
        // Loop through DB clinics and add them in the correct format
        foreach ($clinics as $clinic) {
            $clinicsForJs[] = ['id' => $clinic->id, 'name' => $clinic->name];
        }
        // --- END OF NEW PART ---

        // Get existing schedule data to populate the form
        $schedules = \App\Models\DoctorSchedule::where('doctor_id', $doctorId)->get();

        // Group the schedules by day for the JavaScript
        $scheduleData = [];
        $mainSettings = [
            'start_date' => '',
            'end_date' => '',
            'recurrence' => '',
        ];

        if ($schedules->isNotEmpty()) {
            $firstSchedule = $schedules->first();
            $mainSettings['start_date'] = $firstSchedule->start_date ? Carbon::parse($firstSchedule->start_date)->format('Y-m-d') : '';
            $mainSettings['end_date'] = $firstSchedule->end_date ? Carbon::parse($firstSchedule->end_date)->format('Y-m-d') : '';
            $mainSettings['recurrence'] = $firstSchedule->recurrence;
        }

        foreach ($schedules as $index => $schedule) {
            $day = $schedule->day_of_week;
            if (!isset($scheduleData[$day])) {
                $scheduleData[$day] = [];
            }

            $scheduleData[$day][] = [
                'id' => 'session-' . $index,
                'sessionId' => $index,
                'type' => $schedule->session_type,
                'start' => $schedule->start_time,
                'end' => $schedule->end_time,
                'location' => $schedule->location,
            ];
        }

        // Pass BOTH the original $clinics (for HTML) AND $clinicsForJs (for JS)
        return view('doctor.schedule', compact(
            'notifications',
            'notificationCount',
            'requestCount',
            'scheduleData',
            'mainSettings',
            'clinics', // For the HTML dropdowns created by PHP
            'clinicsForJs' // The clean array specifically for JavaScript
        ));
    }
    /**
     * Save the doctor's schedule
     */
    public function saveSchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'recurrence' => 'required|string',
            'sessions' => 'required|array', // This is the 'daySessions' object from JS
            'sessions.*.*.location' => 'required|string', // <-- Validate the new per-session location
            'sessions.*.*.type' => 'required|string',
            'sessions.*.*.start' => 'required',
            'sessions.*.*.end' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $doctor = Auth::user();
        $data = $request->all();

        try {
            // Step 1: Delete all old schedules for this doctor
            \App\Models\DoctorSchedule::where('doctor_id', $doctor->id)->delete();

            // Step 2: Loop through the new schedule data and create entries
            foreach ($data['sessions'] as $day => $sessions) {
                if (!empty($sessions)) {
                    foreach ($sessions as $session) {
                        // Check if session has all required fields
                        if (isset($session['type']) && isset($session['start']) && isset($session['end']) && isset($session['location'])) {
                            \App\Models\DoctorSchedule::create([
                                'doctor_id' => $doctor->id,
                                'location' => $session['location'], // <-- GET LOCATION FROM THE SESSION
                                'start_date' => $data['start_date'],
                                'end_date' => $data['end_date'],
                                'recurrence' => $data['recurrence'],
                                'day_of_week' => $day, // 'monday', 'tuesday', etc.
                                'session_type' => $session['type'],
                                'start_time' => $session['start'],
                                'end_time' => $session['end'],
                            ]);
                        }
                    }
                }
            }
            
            // --- THIS IS THE "WALKIE-TALKIE" CALL ---
            // The schedule has changed, so we MUST erase the schedule "whiteboard" answer
            Cache::forget("doctor_".Auth::id()."_schedule");

            return response()->json(['success' => true, 'message' => 'Schedule saved successfully!']);

        } catch (\Exception $e) {
            Log::error('Error saving schedule: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while saving.'], 500);
        }
    }
    
    /**
     * Display the leaves page for the doctor
     */
    public function leaves()
    {
        // This is a simple list page, we will not cache it.
        // Get the authenticated doctor's ID
        $doctorId = Auth::user()->id;
        
        // Get unread notifications
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        
        // Get all leave requests for this doctor, sorted by recent first
        $leaves = LeaveRequest::where('user_id', $doctorId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Define leave types
        $leaveTypes = [
            'Casual Leave',
            'Sick Leave',
            'Maternity Leave',
            'Paternity Leave',
            'Compensatory Leave',
            'Emergency Leave',
            'Bereavement Leave',
            'Study/Exam Leave',
            'Paid Leave',
            'Unpaid Leave'
        ];
        
        return view('doctor.leaves', compact('leaves', 'notifications', 'notificationCount', 'requestCount', 'leaveTypes'));
    }
    
    /**
     * Store a new leave request
     */
    public function storeLeave(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'leave_types' => 'required|array|min:1',
                'start_date' => 'required|date|before_or_equal:end_date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'reason' => 'nullable|string|max:1000',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Create a single leave request
            $leave = LeaveRequest::create([
                'user_id' => Auth::user()->id,
                'leave_type' => implode(',', $request->leave_types),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'status' => 'pending'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Leave request submitted successfully',
                'leave' => $leave
            ], 200);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in storeLeave: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit leave request: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update a leave request
     */
    public function updateLeave(Request $request, LeaveRequest $leave)
    {
        try {
            // Check if the leave belongs to the authenticated doctor
            if ($leave->user_id != Auth::user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
            
            // If we're just changing the status to cancelled
            if ($request->has('status') && $request->status == 'cancelled') {
                // Check if the leave is not already approved
                if ($leave->status == 'approved') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot cancel an approved leave request'
                    ], 400);
                }
                
                // Update the leave request status
                $leave->update(['status' => 'cancelled']);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Leave request cancelled successfully'
                ]);
            }
            
            // Validate the request for updating leave details
            $validator = Validator::make($request->all(), [
                'leave_types' => 'required|array|min:1',
                'start_date' => 'required|date|before_or_equal:end_date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'reason' => 'nullable|string|max:1000',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Update the leave request
            $leave->update([
                'leave_type' => implode(',', $request->leave_types),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Leave request updated successfully',
                'leave' => $leave
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in updateLeave: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update leave request: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete a leave request
     */
    public function deleteLeave(LeaveRequest $leave)
    {
        try {
            // Check if the leave belongs to the authenticated doctor
            if ($leave->user_id != Auth::user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
            
            // Check if the leave is not already approved
            if ($leave->status == 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete an approved leave request'
                ], 400);
            }
            
            // Delete the leave request
            $leave->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Leave request deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in deleteLeave: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete leave request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the prescriptions page for the doctor
     */
    public function prescriptions(Request $request)
    {
        // This is a simple list page, we will not cache it.
        // Get the authenticated doctor's ID
        $doctorId = Auth::user()->id;
        
        // Get unread notifications
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        
        // Get search and filter parameters
        $search = $request->get('search');
        $sort = $request->get('sort', 'new'); // new or old
        
        // Build the base query for prescriptions
        $baseQuery = Prescription::with(['patient', 'items.drug'])
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
        
        // Apply sorting
        if ($sort === 'old') {
            $baseQuery->orderBy('created_at', 'asc');
        } else {
            $baseQuery->orderBy('created_at', 'desc');
        }
        
        // Get paginated results
        $prescriptions = $baseQuery->paginate(10)->appends(request()->query());
        
        return view('doctor.prescriptions', compact(
            'prescriptions', 
            'notifications', 
            'notificationCount', 
            'requestCount',
            'search',
            'sort'
        ));
    }
    
    /**
     * Display the specified prescription
     */
    public function showPrescription(Prescription $prescription)
    {
        // This is a "read" page, but it's specific to one item.
        // It's probably fast enough, so we won't cache it for now.
        // Verify the prescription belongs to the authenticated doctor
        if ($prescription->doctor_id !== Auth::user()->id) {
            return redirect()->route('doctor.prescriptions')->with('error', 'Unauthorized access');
        }
        
        // Get unread notifications
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        
        // Load related data
        $prescription->load(['patient', 'items.drug', 'doctor']);
        
        return view('doctor.prescription-show', compact(
            'prescription', 
            'notifications', 
            'notificationCount', 
            'requestCount'
        ));
    }
    
    /**
     * Delete the specified prescription
     */
    public function deletePrescription(Prescription $prescription)
    {
        try {
            // Verify the prescription belongs to the authenticated doctor
            if ($prescription->doctor_id !== Auth::user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }
            
            // Delete the prescription
            $prescription->delete();

            // --- THIS IS THE "WALKIE-TALKIE" CALL ---
            // A prescription was deleted, so clear the recent prescriptions cache
            Cache::forget("doctor_".Auth::id()."_recent_prescriptions");
            
            return response()->json([
                'success' => true,
                'message' => 'Prescription deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in deletePrescription: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete prescription: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Upload lab test result file
     */
    public function uploadLabTestResult(Request $request, \App\Models\LabTest $labTest)
    {
        // Verify the lab test belongs to an appointment with this doctor
        $doctorId = Auth::user()->id;
        if ($labTest->appointment->doctor_id !== $doctorId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }
        
        // Validate the file upload
        $request->validate([
            'test_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif,txt|max:2048',
        ]);
        
        try {
            // Store the file
            $filePath = $request->file('test_file')->store('lab_tests', 'public');
            
            // Update the lab test with the file path
            $labTest->update(['file_path' => $filePath]);
            
            return response()->json([
                'success' => true,
                'message' => 'Lab test result uploaded successfully',
                'file_path' => $filePath
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading lab test result: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX endpoint to get the count of new appointment requests.
     * This is the "phone number" our JavaScript will call.
     */
    public function ajaxGetRequestCount()
    {
        // The total number for the side menu badge should be the sum of new requests AND unread notifications
        // We cache the *result* of the count here for 30 seconds
        $doctorId = Auth::id();
        $totalCount = Cache::remember("doctor_{$doctorId}_total_request_and_notification_count", 30, function () {
            return $this->getAppointmentRequestCount() + $this->getNotificationCount(); // <--- FIXED: Removed (false)
        });
        
        return response()->json(['count' => $totalCount]);
    }

    // Existing helper method for the notification dot (This will be called by AJAX)
    public function ajaxGetNotificationCount()
    {
        // We cache the *result* of the count here for 30 seconds
        $doctorId = Auth::id();
        $count = Cache::remember("doctor_{$doctorId}_just_notification_count", 30, function () {
            return $this->getNotificationCount(); // <--- FIXED: Removed (false)
        });
        
        return response()->json(['count' => $count]);
    }
}