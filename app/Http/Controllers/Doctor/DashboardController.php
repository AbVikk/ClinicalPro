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
use App\Models\PrescriptionItem;
use App\Models\Drug;
use App\Models\User;
use App\Models\Clinic; 
use App\Models\LabTest; 
use App\Models\Consultation;
use App\Models\DoctorSchedule; 
use App\Models\LeaveRequest; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage; 
use \App\Traits\ManagesDoctorCache;

class DashboardController extends Controller
{
    /**
     * Helper to get all cache keys for a specific doctor.
     * This is our "whistleblower" list.
     */
    private function getDoctorCacheKeys($doctorId)
    {
        return [
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
            // AJAX counts
            "doctor_{$doctorId}_total_request_and_notification_count",
            "doctor_{$doctorId}_notification_count"
        ];
    }
    
    /**
     * Helper to flush all cache keys for a doctor.
     */
    private function flushDoctorCache($doctorId)
    {
        $keys = $this->getDoctorCacheKeys($doctorId);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    // Helper method to create a notification
    private function createNotification($userId, $type, $message)
    {
        \App\Models\Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
            'is_read' => false,
            'channel' => 'database', 
        ]);

        // --- FIX: Erase this doctor's cache ---
        $this->flushDoctorCache($userId);
        // --- END FIX ---
    }
    
    // Method to mark all notifications as read
    public function markNotificationsAsRead(Request $request)
    {
        $doctorId = Auth::id();
        Auth::user()->notifications()->update(['is_read' => true]);
        
        // --- FIX ---
        $this->flushDoctorCache($doctorId);
        // --- END FIX ---
        
        return response()->json(['success' => true]);
    }
    
    // Method to mark individual notification as read
    public function markNotificationAsRead(Request $request, $notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        $doctorId = Auth::id();
        
        if ($notification) {
            $notification->update(['is_read' => true]);
            
            // --- FIX ---
            $this->flushDoctorCache($doctorId);
            // --- END FIX ---
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }
    
    // Helper method to get unread notifications (uncached for page load)
    private function getUnreadNotifications()
    {
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
                ->whereIn('status', ['pending', 'new'])
                ->count();
    }
    
    // --- (Helper methods for saveVitals, saveClinicalNotes, etc. remain unchanged) ---
    private function saveVitals(Request $request, $appointmentId, $doctorId) {
        $vitalsData = $request->only(['blood_pressure', 'temperature', 'pulse', 'respiratory_rate', 'spo2', 'height', 'weight', 'waist', 'bsa', 'bmi']);
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
    private function saveMedications(Request $request, $appointmentId) {
        // Delete existing medications for this appointment
        Medication::where('appointment_id', $appointmentId)->delete();
        
        // Get the appointment to get patient and doctor info
        $appointment = Appointment::find($appointmentId);
        if (!$appointment) {
            return;
        }
        
        if ($request->medications) {
            // Create/update prescription
            $prescription = Prescription::updateOrCreate(
                [
                    'patient_id' => $appointment->patient_id,
                    'doctor_id' => $appointment->doctor_id,
                    'consultation_id' => $appointment->consultation_id,
                ],
                [
                    'status' => 'active',
                    'notes' => 'Prescribed during appointment #' . $appointmentId,
                    'refills_allowed' => 0,
                ]
            );
            
            // Clear existing prescription items
            $prescription->items()->delete();
            
            foreach ($request->medications as $medicationData) {
                $medName = $medicationData['name'] ?? null; 
                if (!empty($medName)) { 
                    // Save to medications table (existing functionality)
                    Medication::create([
                        'appointment_id' => $appointmentId,
                        'medication_name' => $medName,
                        'type' => $medicationData['type'] ?? null,
                        'dosage' => $medicationData['dosage'] ?? null,
                        'duration' => $medicationData['duration'] ?? null,
                        'use_pattern' => $medicationData['use_pattern'] ?? null, 
                        'instructions' => $medicationData['instructions'] ?? null,
                    ]);
                    
                    // Also save to prescription items table (new functionality)
                    // Try to find drug by name
                    $drug = Drug::where('name', 'LIKE', "%{$medName}%")->first();
                    
                    PrescriptionItem::create([
                        'prescription_id' => $prescription->id,
                        'drug_id' => $drug ? $drug->id : null,
                        'medication_name' => $medName,
                        'type' => $medicationData['type'] ?? null,
                        'dosage' => $medicationData['dosage'] ?? null,
                        'duration' => $medicationData['duration'] ?? null,
                        'use_pattern' => $medicationData['use_pattern'] ?? null,
                        'instructions' => $medicationData['instructions'] ?? null,
                        'quantity' => 1, // Default quantity
                        'fulfillment_status' => 'pending',
                    ]);
                }
            }
        }
    }
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
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $doctorId = Auth::user()->id;
        
        Appointment::where('doctor_id', $doctorId)
            ->whereIn('status', ['confirmed', 'pending', 'approved', 'checked_in', 'vitals_taken'])
            ->where('appointment_time', '<', now())
            ->update(['status' => 'missed']);

        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        $cacheTime = 3600; // 1 hour
        
        // 1. Get Today's Appointments
        $todaysAppointments = Cache::remember("doctor_{$doctorId}_todays_appointments", $cacheTime, function () use ($doctorId, $today) {
            return Appointment::with(['patient', 'consultation'])
                ->where('doctor_id', $doctorId)
                ->whereDate('appointment_time', $today)
                ->whereIn('status', [
                    'pending', 'confirmed', 'in_progress', 'approved', 'checked_in', 'vitals_taken'
                ])
                ->orderBy('appointment_time')
                ->get();
        });
        $todaysAppointmentsCount = $todaysAppointments->count();

        // 2. Get Upcoming Appointments
        $upcomingAppointments = Cache::remember("doctor_{$doctorId}_upcoming_appointments", $cacheTime, function () use ($doctorId, $tomorrow) {
            return Appointment::with(['patient', 'consultation'])
                ->where('doctor_id', $doctorId)
                ->where('appointment_time', '>=', $tomorrow)
                ->whereIn('status', [
                    'pending', 'confirmed', 'approved', 'checked_in', 'vitals_taken'
                ])
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
        $satisfactionData = []; for ($month = 1; $month <= 12; $month++) { $satisfactionData[] = rand(35, 50) / 10; }
        $currentRating = end($satisfactionData);
        $previousRating = $satisfactionData[count($satisfactionData) - 2] ?? $currentRating;
        $ratingChange = round($currentRating - $previousRating, 1);
        $totalReviews = rand(1000, 1500);
        $reviewsChange = rand(100, 200);
        $recommendationPercentage = rand(85, 95);
        $totalAppointmentsCount = Cache::remember("doctor_{$doctorId}_total_appointments", $cacheTime, function () use ($doctorId) {
            return Appointment::where('doctor_id', $doctorId)->count();
        });
        $onlineConsultationsCount = Cache::remember("doctor_{$doctorId}_online_consultations", $cacheTime, function () use ($doctorId) {
            return Consultation::where('doctor_id', $doctorId)
                ->where('delivery_channel', 'virtual')
                ->whereIn('status', ['scheduled', 'started']) 
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
        $videoConsultationsCount = $onlineConsultationsCount;
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
        $doctorSchedule = Cache::remember("doctor_{$doctorId}_schedule", $cacheTime, function () use ($doctorId) {
            return DoctorSchedule::where('doctor_id', $doctorId)
                ->with('clinic') 
                ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
                ->get()
                ->groupBy('day_of_week');
        });
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
        return view('doctor.dashboard', compact(
            'notifications', 'notificationCount', 'requestCount',
            'todaysAppointmentsCount', 'todaysAppointments', 'upcomingAppointments',
            'pendingTasks', 'recentPrescriptions',
            'patientVisits', 'avgDailyVisits', 'totalYearlyVisits', 'monthlyChange', 'yearlyTrend',
            'satisfactionData', 'currentRating', 'ratingChange', 'totalReviews', 'reviewsChange', 'recommendationPercentage',
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
        $doctorId = Auth::id();
        
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        
        $requests = Appointment::with(['patient', 'appointmentReason'])
            ->where('doctor_id', $doctorId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('doctor.requests', compact('requests', 'notifications', 'notificationCount', 'requestCount'));
    }

    /**
     * Display the appointments page
     */
    public function appointments(Request $request)
    {
        $doctorId = Auth::id();
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        
        $search = $request->get('search');
        $filter = $request->get('filter', 'all'); 
        $tab = $request->get('tab', 'upcoming'); 
        
        if ($request->ajax() || $request->get('ajax')) {
            return $this->getAppointmentsForTab($doctorId, $tab, $search, $filter);
        }
        
        $baseQuery = Appointment::with(['patient', 'appointmentReason', 'consultation']) 
            ->where('doctor_id', $doctorId);
            
        if ($search) {
            $baseQuery->where(function($q) use ($search) {
                $q->whereHas('patient', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        
        if ($filter !== 'all') {
            $baseQuery->where('type', $filter);
        }
        
        $upcomingQuery = clone $baseQuery;
        $inProgressQuery = clone $baseQuery;
        $cancelledQuery = clone $baseQuery;
        $completedQuery = clone $baseQuery;
        $missedQuery = clone $baseQuery; 
        
        $upcomingQuery->whereIn('status', [
                            'confirmed', 'approved', 'checked_in', 'vitals_taken'
                        ])
                      ->where('appointment_time', '>', now());
        
        $inProgressQuery->where('status', 'in_progress');
        $cancelledQuery->where('status', 'cancelled');
        $completedQuery->where('status', 'completed');
        $missedQuery->where('status', 'missed'); 
        
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
            case 'missed': 
                $appointments = $missedQuery->orderBy('appointment_time', 'desc')->paginate($perPage)->appends(request()->query());
                break;
            case 'upcoming':
            default:
                $appointments = $upcomingQuery->orderBy('appointment_time', 'asc')->paginate($perPage)->appends(request()->query());
                break;
        }
        
        $upcomingCount = $upcomingQuery->count();
        $inProgressCount = $inProgressQuery->count();
        $cancelledCount = $cancelledQuery->count();
        $completedCount = $completedQuery->count();
        $missedCount = $missedQuery->count(); 
        
        return view('doctor.appointments', compact(
            'appointments',
            'upcomingCount', 'inProgressCount', 'cancelledCount', 'completedCount', 'missedCount', 
            'search', 'filter', 'tab',
            'notifications', 'notificationCount', 'requestCount'
        ));
    }
    
    /**
     * AJAX function to get tab content
     */
    private function getAppointmentsForTab($doctorId, $tab, $search, $filter)
    {
        $baseQuery = Appointment::with(['patient', 'appointmentReason', 'consultation'])
            ->where('doctor_id', $doctorId);
            
        if ($search) {
            $baseQuery->where(function($q) use ($search) {
                $q->whereHas('patient', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        
        if ($filter !== 'all' && $filter) {
            $baseQuery->where('type', $filter);
        }
        
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
            case 'missed': 
                $baseQuery->where('status', 'missed');
                break;
            case 'upcoming':
            default:
                $baseQuery->whereIn('status', [
                            'confirmed', 'approved', 'checked_in', 'vitals_taken'
                          ])
                          ->where('appointment_time', '>', now());
                break;
        }
        
        $perPage = 10;
        switch ($tab) {
            case 'inprogress':
                $appointments = $baseQuery->orderBy('appointment_time', 'asc')->paginate($perPage);
                break;
            case 'cancelled':
            case 'completed':
            case 'missed': 
                $appointments = $baseQuery->orderBy('appointment_time', 'desc')->paginate($perPage);
                break;
            case 'upcoming':
            default:
                $appointments = $baseQuery->orderBy('appointment_time', 'asc')->paginate($perPage);
                break;
        }
        
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
    public function acceptRequest(Request $request, Appointment $appointment)
    {
        $doctorId = Auth::user()->id;
        if ($appointment->doctor_id !== $doctorId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        try {
            $appointmentTime = $request->appointment_time ?? $appointment->appointment_time;
            
            if (strtotime($appointmentTime) <= time()) {
                $appointmentTime = now()->addHour();
            }

            $newStatus = 'confirmed';
            if ($appointment->type == 'in_person') {
                $newStatus = 'approved'; 
            }
            
            $appointment->update([
                'status' => $newStatus,
                'appointment_time' => $appointmentTime
            ]);
            
            // event(new \App\Events\AdminAlert("New appointment approved, waiting for check-in."));

            // --- FIX ---
            $this->flushDoctorCache($doctorId);
            // --- END FIX ---
            
            return response()->json([
                'success' => true,
                'message' => 'Appointment approved successfully',
                'appointment_time' => $appointment->appointment_time
            ]);
        } catch (\Exception $e) {
            Log::error('Error in acceptRequest: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error accepting appointment: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Reject an appointment request
     */
    public function rejectRequest(Request $request, Appointment $appointment)
    {
        $doctorId = Auth::user()->id;
        if ($appointment->doctor_id !== $doctorId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        try {
            $appointment->update([
                'status' => 'cancelled',
                'cancel_reason' => $request->cancel_reason,
                'cancel_type' => $request->cancel_type
            ]);

            // --- FIX ---
            $this->flushDoctorCache($doctorId);
            // --- END FIX ---

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
        $doctorId = Auth::user()->id;
        if ($appointment->doctor_id !== $doctorId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        try {
            $appointment->update([
                'status' => 'in_progress',
                'started_at' => now()
            ]);
            AppointmentDetail::firstOrCreate([
                'appointment_id' => $appointment->id
            ]);
            
            // Improved error handling for doctor profile update
            try {
                $doctorProfile = Auth::user()->doctorProfile; 
                if ($doctorProfile) {
                    $doctorProfile->live_status = 'In Appointment';
                    $doctorProfile->save();
                } else {
                    Log::warning('Doctor profile not found for user ID: ' . $doctorId);
                }
            } catch (\Exception $e) {
                Log::error('Failed to update live_status on startAppointment: ' . $e->getMessage());
                // Continue with the process even if we can't update live_status
            }

            // --- FIX ---
            $this->flushDoctorCache($doctorId);
            // --- END FIX ---
            
            return response()->json([
                'success' => true,
                'message' => 'Appointment started successfully',
                'status' => 'in_progress',
                'redirect_url' => route('doctor.appointments.details', $appointment->id)
            ]);
        } catch (\Exception $e) {
            Log::error('Error starting appointment: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            return response()->json(['success' => false, 'message' => 'Error starting appointment. Please try again.'], 500);
        }
    }
    
    public function endAppointment(Request $request, Appointment $appointment)
    {
        $doctorId = Auth::user()->id;
        if ($appointment->doctor_id !== $doctorId) {
            if ($request->ajax()) { return response()->json(['success' => false, 'message' => 'Unauthorized'], 403); }
            return redirect()->route('doctor.appointments')->with('error', 'Unauthorized access');
        }
        if ($request->isMethod('get')) {
            $notifications = $this->getUnreadNotifications();
            $notificationCount = $this->getNotificationCount();
            $requestCount = $this->getAppointmentRequestCount();
            return view('doctor.confirm-end-appointment', compact('appointment', 'notifications', 'notificationCount', 'requestCount'));
        }
        $request->validate(['end_reason' => 'required|string|max:500']);
        try {
            $appointment->update([
                'status' => 'completed',
                'completed_at' => now(),
                'end_reason' => $request->end_reason
            ]);
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
            
            // Improved error handling for doctor profile update
            try {
                $doctorProfile = Auth::user()->doctorProfile; 
                if ($doctorProfile) {
                    $doctorProfile->live_status = 'Available';
                    $doctorProfile->save();
                } else {
                    Log::warning('Doctor profile not found for user ID: ' . $doctorId);
                }
            } catch (\Exception $e) {
                Log::error('Failed to update live_status on endAppointment: ' . $e->getMessage());
                // Continue with the process even if we can't update live_status
            }

            // --- FIX ---
            $this->flushDoctorCache($doctorId);
            // --- END FIX ---

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Appointment completed successfully',
                    'status' => 'completed'
                ]);
            }
            return redirect()->route('doctor.dashboard')->with('success', 'Appointment completed successfully');
        } catch (\Exception $e) {
            Log::error('Error completing appointment: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            if ($request->ajax()) { return response()->json(['success' => false, 'message' => 'Error completing appointment. Please try again.'], 500); }
            return redirect()->back()->with('error', 'Error completing appointment. Please try again.');
        }
    }
    public function getRequestCount()
    {
        $count = $this->getAppointmentRequestCount() + $this->getNotificationCount();
        return response()->json(['count' => $count]);
    }
    public function showPatient($id)
    {
        $doctorId = Auth::user()->id;
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        $patient = User::where('role', 'patient')
            ->whereHas('appointmentsAsPatient', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->findOrFail($id);
        $recentAppointments = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', $doctorId)
            ->with(['appointmentReason'])
            ->orderBy('appointment_time', 'desc')
            ->limit(3)
            ->get();
        $allAppointments = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', $doctorId)
            ->with(['appointmentReason'])
            ->orderBy('appointment_time', 'desc')
            ->get();
        $nextAppointment = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', $doctorId)
            ->where('status', 'confirmed')
            ->where('appointment_time', '>=', now())
            ->with(['appointmentReason'])
            ->orderBy('appointment_time', 'asc')
            ->first();
        $patientPrescriptions = Prescription::where('patient_id', $patient->id)
            ->where('doctor_id', $doctorId)
            ->with(['items.drug'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('doctor.patient-profile', compact('patient', 'recentAppointments', 'allAppointments', 'patientPrescriptions', 'nextAppointment', 'notifications', 'notificationCount', 'requestCount'));
    }
    public function indexPatient(Request $request)
    {
        $doctorId = Auth::user()->id;
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        $query = User::where('role', 'patient')
            ->whereHas('appointmentsAsPatient', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->with(['appointmentsAsPatient' => function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId)
                    ->orderBy('appointment_time', 'desc');
            }])
            ->orderBy('name');
        if ($request->ajax()) {
            $patients = $query->get();
        } else {
            $search = $request->get('search');
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            }
            $patients = $query->paginate(20);
        }
        if ($request->ajax()) {
            return response()->view('doctor.patients-table', compact('patients'));
        }
        return view('doctor.patients', compact('patients', 'notifications', 'notificationCount', 'requestCount'));
    }
    public function showAppointmentDetails(Appointment $appointment)
    {
        if ($appointment->doctor_id !== Auth::user()->id) {
            return redirect()->route('doctor.appointments')->with('error', 'Unauthorized access');
        }
        if ($appointment->status !== 'in_progress') {
            return redirect()->route('doctor.appointments')->with('error', 'Appointment is not in progress');
        }
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        $appointmentDetail = AppointmentDetail::firstOrCreate([
            'appointment_id' => $appointment->id
        ]);
        $appointment->load(['vitals', 'clinicalNote', 'medications', 'labTests', 'doctor', 'patient', 'appointmentReason', 'appointmentDetail', 'consultation.clinic']);
        $drugs = \App\Models\Drug::orderBy('name')->get();
        $categories = \App\Models\DrugCategory::orderBy('name')->get();
        $dosages = \App\Models\DrugMg::orderBy('mg_value')->get();
        $noOfVisits = \App\Models\Appointment::where('patient_id', $appointment->patient_id)
            ->where('doctor_id', $appointment->doctor_id)
            ->where('status', 'completed')
            ->count();
        return view('doctor.appointment-details', compact('appointment', 'appointmentDetail', 'drugs', 'categories', 'dosages', 'notifications', 'notificationCount', 'requestCount', 'noOfVisits'));
    }
    public function saveAppointmentDetails(Request $request, Appointment $appointment)
    {
        $doctorId = Auth::user()->id;
        if ($appointment->doctor_id !== $doctorId) {
            if ($request->ajax()) { return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403); }
            return redirect()->route('doctor.appointments')->with('error', 'Unauthorized access');
        }
        $isPartialUpdate = (
                          $request->has('complaints') || $request->has('diagnosis') || $request->has('medications') || 
                          $request->has('lab_tests') || $request->has('blood_group') || $request->has('advice') ||
                          $request->has('follow_up_date') || $request->has('follow_up_time') ||
                          $request->has('clinical_notes') || $request->has('skin_allergy') ||
                          $request->hasAny(['blood_pressure', 'temperature', 'pulse', 'respiratory_rate', 'spo2', 'height', 'weight', 'waist', 'bsa', 'bmi'])
        ) && !$request->has('is_full_update'); 
        $validatorRules = [
            'blood_group' => 'nullable|string|max:10', 'advice' => 'nullable|string|max:1000',
            'follow_up_date' => 'nullable|date', 'follow_up_time' => 'nullable|date_format:H:i',
            'blood_pressure' => 'nullable|string|max:20', 'temperature' => 'nullable|string|max:10',
            'pulse' => 'nullable|string|max:10', 'respiratory_rate' => 'nullable|string|max:10',
            'spo2' => 'nullable|string|max:10', 'height' => 'nullable|string|max:10',
            'weight' => 'nullable|string|max:10', 'waist' => 'nullable|string|max:10',
            'bsa' => 'nullable|string|max:10', 'bmi' => 'nullable|string|max:10',
            'clinical_notes' => 'nullable|string|max:1000', 'skin_allergy' => 'nullable|string|max:500',
            'medications' => 'nullable|array', 'medications.*.name' => 'nullable|string|max:100',
            'medications.*.type' => 'nullable|string|max:50', 'medications.*.dosage' => 'nullable|string|max:50',
            'medications.*.duration' => 'nullable|string|max:50', 'medications.*.instructions' => 'nullable|string|max:200',
            'lab_tests' => 'nullable|array', 'lab_tests.*.file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif,txt|max:2048', 
            'lab_tests.*.name' => 'nullable|string|max:100',
            'complaints' => 'nullable|array', 'complaints.*' => 'nullable|string|max:200',
            'diagnosis' => 'nullable|array', 'diagnosis.*' => 'nullable|string|max:200',
        ];
        $validator = Validator::make($request->all(), $validatorRules);
        if ($validator->fails()) {
            if ($request->ajax()) { return response()->json(['success' => false, 'message' => 'Validation failed: ' . implode(', ', $validator->errors()->all())], 422); }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $appointmentDetail = AppointmentDetail::firstOrCreate(['appointment_id' => $appointment->id]);
            if ($isPartialUpdate) {
                $this->saveVitals($request, $appointment->id, $doctorId);
                $this->saveClinicalNotes($request, $appointment->id, $doctorId);
                if ($request->has('medications')) {
                    $this->saveMedications($request, $appointment->id);
                    // --- FIX ---
                    $this->flushDoctorCache($doctorId);
                    // --- END FIX ---
                }
                if ($request->has('lab_tests')) {
                    $this->saveLabTests($request, $appointment->id, $doctorId);
                }
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
            $this->saveVitals($request, $appointment->id, $doctorId);
            $this->saveClinicalNotes($request, $appointment->id, $doctorId);
            $this->saveMedications($request, $appointment->id);
            $this->saveLabTests($request, $appointment->id, $doctorId);
            $appointmentDetail->update([
                'blood_group' => $request->blood_group, 'advice' => $request->advice,
                'follow_up_date' => $request->follow_up_date, 'follow_up_time' => $request->follow_up_time,
                'complaints' => $request->complaints, 'diagnosis' => $request->diagnosis,
            ]);
            $appointment->update([
                'status' => 'completed',
                'completed_at' => now(),
                'end_reason' => 'Session completed by doctor'
            ]);
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
            try {
                $doctorProfile = Auth::user()->doctorProfile; 
                if ($doctorProfile) {
                    $doctorProfile->live_status = 'Available';
                    $doctorProfile->save();
                }
            } catch (\Exception $e) {
                Log::error('Failed to update live_status on saveAppointmentDetails: ' . $e->getMessage());
            }

            // --- FIX ---
            $this->flushDoctorCache($doctorId);
            // --- END FIX ---
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Appointment details saved successfully and session completed.',
                    'redirect_url' => route('doctor.dashboard')
                ]);
            }
            return redirect()->route('doctor.dashboard')->with('success', 'Appointment details saved successfully and session completed.');
        } catch (\Exception $e) {
            Log::error('Error saving appointment details: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            if ($request->ajax()) { return response()->json(['success' => false, 'message' => 'Error saving appointment details: ' . $e->getMessage()], 500); }
            return redirect()->back()->with('error', 'Error saving appointment details: ' . $e->getMessage())->withInput();
        }
    }
    public function showAppointmentHistory($patientId)
    {
        $doctorId = Auth::user()->id;
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        $patient = User::where('role', 'patient')
            ->whereHas('appointmentsAsPatient', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->findOrFail($patientId);
        $completedAppointments = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->with([
                'doctor', 'patient', 'appointmentReason', 'vitals', 
                'clinicalNote', 'medications', 'labTests', 
                'appointmentDetail', 'consultation.clinic'
            ])
            ->orderBy('appointment_time', 'desc')
            ->get();
        $totalVisits = $completedAppointments->count();
        if ($completedAppointments->isEmpty()) {
            return redirect()->back()->with('error', 'No completed appointments found for this patient.');
        }
        $currentAppointment = $completedAppointments->first();
        $currentAppointmentDetail = $currentAppointment->appointmentDetail ?? new AppointmentDetail();
        $appointmentsData = [];
        foreach ($completedAppointments as $appointment) {
            $appointmentDetail = $appointment->appointmentDetail ?? new AppointmentDetail();
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
            $appointmentsData[] = [
                'id' => $appointment->id,
                'patient_name' => $appointment->patient?->name ?? 'Unknown Patient',
                'patient_email' => $appointment->patient?->email ?? '',
                'patient_phone' => $appointment->patient?->phone ?? '',
                'doctor_name' => $appointment->doctor?->name ?? 'Unknown Doctor',
                'type_display' => $typeDisplay,
                'clinic_display' => $clinicDisplay,
                'location_display' => $locationDisplay,
                'service_display' => $serviceDisplay,
                'duration_display' => $durationDisplay,
                'status' => $appointment->status,
                'consultation_fee' => $appointment->consultation?->fee ?? $appointment->consultation_fee ?? 'N/A',
                'appointment_time' => $appointment->appointment_time->toIso8601String(), // Send as ISO string for JS
                'blood_group' => $appointmentDetail->blood_group,
                'clinical_notes' => $appointment->clinicalNote?->note_text ?? '',
                'skin_allergy' => $appointment->clinicalNote?->skin_allergy ?? '',
                'advice' => $appointmentDetail->advice,
                'follow_up_date' => $appointmentDetail->follow_up_date ? Carbon::parse($appointmentDetail->follow_up_date)->format('Y-m-d') : null,
                'follow_up_time' => $appointmentDetail->follow_up_time ? Carbon::parse($appointmentDetail->follow_up_time)->format('H:i') : null, // Format as HH:MM
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
                'complaints' => $appointmentDetail->complaints ?? [],
                'diagnosis' => $appointmentDetail->diagnosis ?? [],
                'lab_tests' => $labTestsData,
                'medications' => $appointment->medications->map(function($med) {
                    return [
                        'medication_name' => $med->medication_name,
                        'type' => $med->type,
                        'dosage' => $med->dosage,
                        'duration' => $med->duration,
                        'use_pattern' => $med->use_pattern,
                        'instructions' => $med->instructions
                    ];
                })->toArray()
            ];
        }
        return view('doctor.appointment-history', compact(
            'patient', 'completedAppointments', 'currentAppointment', 
            'currentAppointmentDetail', 'totalVisits',
            'notifications', 'notificationCount', 'requestCount',
            'appointmentsData'
        ));
    }
    public function checkFollowUpAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'follow_up_date' => 'required|date_format:Y-m-d',
            'follow_up_time' => 'required|date_format:H:i',
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
        $isScheduled = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where(DB::raw('CAST(start_time AS TIME)'), '<=', $time)
            ->where(DB::raw('CAST(end_time AS TIME)'), '>', $time)
            ->exists();
        if (!$isScheduled) {
            return response()->json(['available' => false, 'message' => 'Doctor is not scheduled to work at this time.']);
        }
        $assumedDuration = 30;
        $followUpEnd = $selectedDateTime->copy()->addMinutes($assumedDuration);
        $hasConflict = Consultation::where('doctor_id', $doctorId)
            ->whereNotIn('status', ['completed', 'missed', 'cancelled'])
            ->where(function ($query) use ($selectedDateTime, $followUpEnd) {
                $query->where('start_time', '<', $followUpEnd)
                      ->where(DB::raw('DATE_ADD(start_time, INTERVAL duration_minutes MINUTE)'), '>', $selectedDateTime);
            })
            ->exists();
        if ($hasConflict) {
            return response()->json(['available' => false, 'message' => 'Doctor has a conflicting appointment at this time.']);
        }
        return response()->json(['available' => true, 'message' => 'Time slot appears available.']);
    }
    public function schedule()
    {
        $doctorId = Auth::user()->id;
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        $clinics = \App\Models\Clinic::where('is_physical', 1)->get();
        $clinicsForJs = [];
        $clinicsForJs[] = ['id' => 'virtual', 'name' => 'Virtual Session'];
        foreach ($clinics as $clinic) {
            $clinicsForJs[] = ['id' => $clinic->id, 'name' => $clinic->name];
        }
        $schedules = \App\Models\DoctorSchedule::where('doctor_id', $doctorId)->get();
        $scheduleData = [];
        $mainSettings = [
            'start_date' => '', 'end_date' => '', 'recurrence' => '',
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
        return view('doctor.schedule', compact(
            'notifications', 'notificationCount', 'requestCount',
            'scheduleData', 'mainSettings', 'clinics', 'clinicsForJs' 
        ));
    }
    public function saveSchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'recurrence' => 'required|string',
            'sessions' => 'required|array', 
            'sessions.*.*.location' => 'required|string', 
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
            \App\Models\DoctorSchedule::where('doctor_id', $doctor->id)->delete();
            foreach ($data['sessions'] as $day => $sessions) {
                if (!empty($sessions)) {
                    foreach ($sessions as $session) {
                        if (isset($session['type']) && isset($session['start']) && isset($session['end']) && isset($session['location'])) {
                            \App\Models\DoctorSchedule::create([
                                'doctor_id' => $doctor->id,
                                'location' => $session['location'], 
                                'start_date' => $data['start_date'],
                                'end_date' => $data['end_date'],
                                'recurrence' => $data['recurrence'],
                                'day_of_week' => $day, 
                                'session_type' => $session['type'],
                                'start_time' => $session['start'],
                                'end_time' => $session['end'],
                            ]);
                        }
                    }
                }
            }

            // --- FIX ---
            $this->flushDoctorCache($doctor->id);
            // --- END FIX ---

            return response()->json(['success' => true, 'message' => 'Schedule saved successfully!']);
        } catch (\Exception $e) {
            Log::error('Error saving schedule: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while saving.'], 500);
        }
    }
    public function leaves()
    {
        $doctorId = Auth::user()->id;
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        $leaves = LeaveRequest::where('user_id', $doctorId)
            ->orderBy('created_at', 'desc')
            ->get();
        $leaveTypes = [
            'Casual Leave', 'Sick Leave', 'Maternity Leave', 'Paternity Leave', 'Compensatory Leave',
            'Emergency Leave', 'Bereavement Leave', 'Study/Exam Leave', 'Paid Leave', 'Unpaid Leave'
        ];
        return view('doctor.leaves', compact('leaves', 'notifications', 'notificationCount', 'requestCount', 'leaveTypes'));
    }
    public function storeLeave(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'leave_types' => 'required|array|min:1',
                'start_date' => 'required|date|before_or_equal:end_date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'reason' => 'nullable|string|max:1000',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()
                ], 422);
            }
            $leave = LeaveRequest::create([
                'user_id' => Auth::user()->id,
                'leave_type' => implode(',', $request->leave_types),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'status' => 'pending'
            ]);
            return response()->json([
                'success' => true, 'message' => 'Leave request submitted successfully', 'leave' => $leave
            ], 200);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in storeLeave: ' . $e->getMessage());
            return response()->json([
                'success' => false, 'message' => 'Failed to submit leave request: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateLeave(Request $request, LeaveRequest $leave)
    {
         try {
            if ($leave->user_id != Auth::user()->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
            }
            if ($request->has('status') && $request->status == 'cancelled') {
                if ($leave->status == 'approved') {
                    return response()->json(['success' => false, 'message' => 'Cannot cancel an approved leave request'], 400);
                }
                $leave->update(['status' => 'cancelled']);
                return response()->json(['success' => true, 'message' => 'Leave request cancelled successfully']);
            }
            $validator = Validator::make($request->all(), [
                'leave_types' => 'required|array|min:1',
                'start_date' => 'required|date|before_or_equal:end_date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'reason' => 'nullable|string|max:1000',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }
            $leave->update([
                'leave_type' => implode(',', $request->leave_types),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason
            ]);
            return response()->json(['success' => true, 'message' => 'Leave request updated successfully', 'leave' => $leave]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in updateLeave: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update leave request: ' . $e->getMessage()], 500);
        }
    }
    public function deleteLeave(LeaveRequest $leave)
    {
        try {
            if ($leave->user_id != Auth::user()->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
            }
            if ($leave->status == 'approved') {
                return response()->json(['success' => false, 'message' => 'Cannot delete an approved leave request'], 400);
            }
            $leave->delete();
            return response()->json(['success' => true, 'message' => 'Leave request deleted successfully']);
        } catch (\Exception $e) {
            // --- THIS IS THE LINE I FIXED ---
            \Illuminate\Support\Facades\Log::error('Error in deleteLeave: ' . $e->getMessage());
            // --- END OF FIX ---
            return response()->json(['success' => false, 'message' => 'Failed to delete leave request: ' . $e->getMessage()], 500);
        }
    }
    public function prescriptions(Request $request)
    {
        $doctorId = Auth::user()->id;
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        $search = $request->get('search');
        $sort = $request->get('sort', 'new');
        $baseQuery = Prescription::with(['patient', 'items.drug'])
            ->where('doctor_id', $doctorId);
        if ($search) {
            $baseQuery->where(function($q) use ($search) {
                $q->whereHas('patient', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        if ($sort === 'old') {
            $baseQuery->orderBy('created_at', 'asc');
        } else {
            $baseQuery->orderBy('created_at', 'desc');
        }
        $prescriptions = $baseQuery->paginate(10)->appends(request()->query());
        return view('doctor.prescriptions', compact(
            'prescriptions', 'notifications', 'notificationCount', 'requestCount', 'search', 'sort'
        ));
    }
    public function showPrescription(Prescription $prescription)
    {
        if ($prescription->doctor_id !== Auth::user()->id) {
            return redirect()->route('doctor.prescriptions')->with('error', 'Unauthorized access');
        }
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount();
        $prescription->load(['patient', 'items.drug', 'doctor', 'consultation']);
        return view('doctor.prescription-show', compact(
            'prescription', 'notifications', 'notificationCount', 'requestCount'
        ));
    }
    public function deletePrescription(Prescription $prescription)
    {
         try {
            if ($prescription->doctor_id !== Auth::user()->id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
            }
            $prescription->delete();

            // --- FIX ---
            $this->flushDoctorCache(Auth::id());
            // --- END FIX ---

            return response()->json(['success' => true, 'message' => 'Prescription deleted successfully']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in deletePrescription: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete prescription: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Generate PDF for a prescription
     */
    public function printPrescription($prescriptionId)
    {
        $prescription = \App\Models\Prescription::with(['patient', 'doctor.doctorProfile', 'items.drug', 'consultation'])
            ->where('doctor_id', Auth::id())
            ->findOrFail($prescriptionId);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.prescription', compact('prescription'));
        
        // Download the file with a nice name like "Prescription-JohnDoe-2024.pdf"
        $filename = 'Prescription-' . \Illuminate\Support\Str::slug($prescription->patient->name) . '-' . now()->format('Ymd') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function uploadLabTestResult(Request $request, \App\Models\LabTest $labTest)
    {
        $doctorId = Auth::user()->id;
        if ($labTest->appointment->doctor_id !== $doctorId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
        }
        $request->validate([
            'test_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif,txt|max:2048',
        ]);
        try {
            $filePath = $request->file('test_file')->store('lab_tests', 'public');
            $labTest->update(['file_path' => $filePath]);
            return response()->json([
                'success' => true, 'message' => 'Lab test result uploaded successfully', 'file_path' => $filePath
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 'message' => 'Error uploading lab test result: ' . $e->getMessage()
            ], 500);
        }
    }
    public function ajaxGetRequestCount()
    {
        $doctorId = Auth::id();
        
        $totalCount = Cache::remember("doctor_{$doctorId}_total_request_and_notification_count", 30, function () {
            // We must use Auth::id() inside the closure or pass $doctorId
            return $this->getAppointmentRequestCount() + $this->getNotificationCount(); 
        });
        
        return response()->json(['count' => $totalCount]);
    }
    public function ajaxGetNotificationCount()
    {
        $doctorId = Auth::id();

        $count = Cache::remember("doctor_{$doctorId}_notification_count", 30, function () {
            // We must use Auth::id() inside the closure or pass $doctorId
            return $this->getNotificationCount(); 
        });
        
        return response()->json(['count' => $count]);
    }
}