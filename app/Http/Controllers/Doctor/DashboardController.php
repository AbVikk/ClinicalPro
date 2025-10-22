<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Appointment;
use App\Models\AppointmentDetail;
use App\Models\Vitals;
use App\Models\ClinicalNote;
use App\Models\Medication;
use App\Models\Prescription;
use App\Models\User;

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
    }
    
    // Method to mark all notifications as read
    public function markNotificationsAsRead(Request $request)
    {
        Auth::user()->notifications()->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
    
    // Method to mark individual notification as read
    public function markNotificationAsRead(Request $request, $notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->update(['is_read' => true]);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }
    
    // Helper method to get unread notifications for the authenticated user
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
        return Appointment::where('doctor_id', Auth::user()->id)
            ->whereIn('status', ['pending', 'new'])
            ->count();
    }
    
    public function index()
    {
        // Get today's date
        $today = Carbon::today();
        
        // Get tomorrow's date
        $tomorrow = Carbon::tomorrow();
        
        // Get the authenticated doctor's ID
        $doctorId = Auth::user()->id;
        
        // Get unread notifications
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount(); // Add this line
        
        // Get today's appointments count
        $todaysAppointmentsCount = Appointment::whereDate('appointment_time', $today)
            ->where('doctor_id', $doctorId)
            ->count();
        
        // Get today's appointments (limit 5)
        $todaysAppointments = Appointment::with(['patient.patient'])
            ->whereDate('appointment_time', $today)
            ->where('doctor_id', $doctorId)
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();
        
        // Get upcoming appointments (from tomorrow onward, limit 5)
        $upcomingAppointments = Appointment::with(['patient.patient'])
            ->where('appointment_time', '>=', $tomorrow)
            ->where('doctor_id', $doctorId)
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();
        
        // Get today's patients (patients with appointments today)
        $todaysPatients = Appointment::with(['patient.patient'])
            ->whereDate('appointment_time', $today)
            ->where('doctor_id', $doctorId)
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();
        
        // Get recent prescriptions with notes (limit 5)
        $recentNotes = Prescription::with(['patient.patient', 'doctor'])
            ->where('doctor_id', $doctorId)
            ->whereNotNull('notes')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get pending tasks (using appointments as tasks for now, limit 5)
        $pendingTasks = Appointment::with(['patient.patient'])
            ->where('doctor_id', $doctorId)
            ->where('status', '!=', 'completed')
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();
        
        // Get recent prescriptions (limit 5)
        $recentPrescriptions = Prescription::with(['patient', 'items.drug'])
            ->where('doctor_id', $doctorId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get patient visit statistics for the current year
        $currentYear = Carbon::now()->year;
        $patientVisits = [];
        $totalYearlyVisits = 0;
        
        for ($month = 1; $month <= 12; $month++) {
            $visitCount = Appointment::where('doctor_id', $doctorId)
                ->whereYear('appointment_time', $currentYear)
                ->whereMonth('appointment_time', $month)
                ->where('status', 'completed')
                ->count();
            
            $patientVisits[] = $visitCount;
            $totalYearlyVisits += $visitCount;
        }
        
        // Calculate average daily visits (assuming 30 days per month for simplicity)
        $avgDailyVisits = $totalYearlyVisits > 0 ? round($totalYearlyVisits / 365, 1) : 0;
        
        // Get last month's visits for comparison
        $lastMonth = Carbon::now()->subMonth();
        $lastMonthVisits = Appointment::where('doctor_id', $doctorId)
            ->whereYear('appointment_time', $lastMonth->year)
            ->whereMonth('appointment_time', $lastMonth->month)
            ->where('status', 'completed')
            ->count();
        
        // Calculate monthly change
        $monthlyChange = $patientVisits[Carbon::now()->month - 1] - $lastMonthVisits;
        
        // Calculate yearly trend (simplified - comparing this year to last year)
        $lastYearTotal = 0;
        for ($month = 1; $month <= 12; $month++) {
            $visitCount = Appointment::where('doctor_id', $doctorId)
                ->whereYear('appointment_time', $currentYear - 1)
                ->whereMonth('appointment_time', $month)
                ->where('status', 'completed')
                ->count();
            $lastYearTotal += $visitCount;
        }
        
        $yearlyTrend = $lastYearTotal > 0 ? round((($totalYearlyVisits - $lastYearTotal) / $lastYearTotal) * 100, 1) : 0;
        
        // For patient satisfaction, we'll simulate data since there's no rating system
        // In a real implementation, this would come from a ratings table
        $satisfactionData = [];
        $totalRating = 0;
        for ($month = 1; $month <= 12; $month++) {
            // Generate simulated rating between 3.5 and 5.0
            $rating = rand(35, 50) / 10;
            $satisfactionData[] = $rating;
            $totalRating += $rating;
        }
        
        // Calculate current rating (last month)
        $currentRating = end($satisfactionData);
        
        // Calculate previous month rating for comparison
        $previousRating = count($satisfactionData) > 1 ? $satisfactionData[count($satisfactionData) - 2] : $currentRating;
        $ratingChange = round($currentRating - $previousRating, 1);
        
        // Total reviews (simulated)
        $totalReviews = rand(1000, 1500);
        $reviewsChange = rand(100, 200);
        
        // Recommendation percentage (simulated)
        $recommendationPercentage = rand(85, 95);
        
        return view('doctor.dashboard', compact(
            'todaysAppointmentsCount', 
            'todaysAppointments', 
            'upcomingAppointments',
            'todaysPatients',
            'recentNotes',
            'pendingTasks',
            'recentPrescriptions',
            'patientVisits',
            'avgDailyVisits',
            'totalYearlyVisits',
            'monthlyChange',
            'yearlyTrend',
            'satisfactionData',
            'currentRating',
            'ratingChange',
            'totalReviews',
            'reviewsChange',
            'recommendationPercentage',
            'notifications',
            'notificationCount',
            'requestCount'
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
        $requests = Appointment::with(['patient.patient', 'appointmentReason'])
            ->where('doctor_id', $doctorId)
            ->where('status', 'pending') // Changed from 'confirmed' to 'pending'
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('doctor.requests', compact('requests', 'notifications', 'notificationCount', 'requestCount'));
    }

    /**
     * Display the appointments page for the doctor
     */
    public function appointments(Request $request)
    {
        // Get the authenticated doctor's ID
        $doctorId = Auth::user()->id;
        
        // Get unread notifications
        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = $this->getAppointmentRequestCount(); // Add this line
        
        // Get filter parameters
        $search = $request->get('search');
        $filter = $request->get('filter', 'all'); // all, chat, direct
        $tab = $request->get('tab', 'upcoming'); // upcoming, inprogress, cancelled, completed
        
        // Check if this is an AJAX request for a specific tab
        if ($request->ajax() || $request->get('ajax')) {
            return $this->getAppointmentsForTab($doctorId, $tab, $search, $filter);
        }
        
        // Build the base query for appointments
        $baseQuery = Appointment::with(['patient.patient', 'appointmentReason'])
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
        $inProgressCount = $inProgressQuery->count();
        $cancelledCount = $cancelledQuery->count();
        $completedCount = $completedQuery->count();
        
        return view('doctor.appointments', compact(
            'appointments',
            'upcomingCount', 
            'inProgressCount',
            'cancelledCount', 
            'completedCount',
            'search',
            'filter',
            'tab',
            'notifications',
            'notificationCount',
            'requestCount'
        ));
    }
    
    /**
     * Get appointments for a specific tab via AJAX
     */
    private function getAppointmentsForTab($doctorId, $tab, $search, $filter)
    {
        // Build the base query for appointments
        $baseQuery = Appointment::with(['patient.patient', 'appointmentReason'])
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
            case 'upcoming':
                $baseQuery->where('status', 'confirmed')
                          ->where('appointment_time', '>', now());
                break;
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
                $appointments = $baseQuery->orderBy('appointment_time', 'desc')->paginate($perPage);
                break;
            case 'completed':
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
            $appointmentDetail = AppointmentDetail::firstOrCreate([
                'appointment_id' => $appointment->id
            ]);
            
            // Redirect to appointment details page
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
                $consultation = \App\Models\Consultation::find($payment->consultation_id);
                if ($consultation) {
                    $consultation->update([
                        'end_time' => now(),
                        'status' => 'completed'
                    ]);
                }
            }
            
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
        $doctorId = Auth::user()->id;
        
        // Count both unread notifications and pending appointments
        $notificationCount = $this->getNotificationCount();
        $pendingAppointmentCount = Appointment::where('doctor_id', $doctorId)
            ->whereIn('status', ['pending', 'new'])
            ->count();
            
        $count = $notificationCount + $pendingAppointmentCount;
            
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
        $appointment->load(['vitals', 'clinicalNote', 'medications', 'doctor', 'patient', 'appointmentReason']);
        
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
        if ($appointment->doctor_id !== Auth::user()->id) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access'], 403);
            }
            return redirect()->route('doctor.appointments')->with('error', 'Unauthorized access');
        }
        
        // Validate the request
        $request->validate([
            'blood_group' => 'nullable|string|max:10',
            'advice' => 'nullable|string|max:1000',
            'follow_up_date' => 'nullable|date',
            'follow_up_time' => 'nullable|date_format:H:i',
            // Vitals validation
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
            // Medications validation
            'medications' => 'nullable|array',
            'medications.*.name' => 'nullable|string|max:100',
            'medications.*.type' => 'nullable|string|max:50',
            'medications.*.dosage' => 'nullable|string|max:50',
            'medications.*.duration' => 'nullable|string|max:50',
            'medications.*.instructions' => 'nullable|string|max:200', // Fixed: was 'instruction', should be 'instructions'
            // Lab tests validation
            'lab_tests' => 'nullable|array',
            'lab_tests.*.name' => 'nullable|string|max:100',
            'lab_tests.*.file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
            // Complaints and diagnosis validation
            'complaints' => 'nullable|array',
            'complaints.*' => 'nullable|string|max:200',
            'diagnosis' => 'nullable|array',
            'diagnosis.*' => 'nullable|string|max:200',
        ]);
        
        try {
            // Get or create appointment details
            $appointmentDetail = AppointmentDetail::firstOrCreate([
                'appointment_id' => $appointment->id
            ]);
            
            // Handle lab tests with files
            $labTestsData = [];
            if ($request->lab_tests) {
                foreach ($request->lab_tests as $labTest) {
                    if (!empty($labTest['name'])) {
                        $labTestEntry = [
                            'name' => $labTest['name'],
                            'file_path' => null
                        ];
                        
                        // Handle file upload if present
                        if (isset($labTest['file']) && $labTest['file'] instanceof \Illuminate\Http\UploadedFile) {
                            $filePath = $labTest['file']->store('lab_tests', 'public');
                            $labTestEntry['file_path'] = $filePath;
                        } elseif (isset($labTest['file_path'])) {
                            // Keep existing file path
                            $labTestEntry['file_path'] = $labTest['file_path'];
                        }
                        
                        $labTestsData[] = $labTestEntry;
                    }
                }
            }
            
            // Handle complaints
            $complaintsData = [];
            if ($request->complaints && is_array($request->complaints)) {
                foreach ($request->complaints as $complaint) {
                    if (!empty($complaint)) {
                        $complaintsData[] = $complaint;
                    }
                }
            }
            
            // Handle diagnosis
            $diagnosisData = [];
            if ($request->diagnosis && is_array($request->diagnosis)) {
                foreach ($request->diagnosis as $diagnosis) {
                    if (!empty($diagnosis)) {
                        $diagnosisData[] = $diagnosis;
                    }
                }
            }
            
            // Update appointment details
            $appointmentDetail->update([
                'blood_group' => $request->blood_group,
                'lab_tests' => $labTestsData,
                'complaints' => $complaintsData,
                'diagnosis' => $diagnosisData,
                'advice' => $request->advice,
                'follow_up_date' => $request->follow_up_date,
                'follow_up_time' => $request->follow_up_time,
            ]);
            
            // Create or update vitals
            $vitals = Vitals::updateOrCreate(
                ['appointment_id' => $appointment->id],
                [
                    'temperature' => $request->temperature,
                    'pulse' => $request->pulse,
                    'respiratory_rate' => $request->respiratory_rate,
                    'spo2' => $request->spo2,
                    'height' => $request->height,
                    'weight' => $request->weight,
                    'waist' => $request->waist,
                    'bsa' => $request->bsa,
                    'bmi' => $request->bmi,
                    'recorded_at' => now(),
                ]
            );
            
            // Create or update clinical notes
            $clinicalNote = ClinicalNote::updateOrCreate(
                ['appointment_id' => $appointment->id],
                [
                    'doctor_id' => Auth::user()->id,
                    'note_text' => $request->clinical_notes ?? '',
                    'skin_allergy' => $request->skin_allergy,
                ]
            );
            
            // Handle medications
            // First, delete existing medications for this appointment
            Medication::where('appointment_id', $appointment->id)->delete();
            
            // Then create new medications
            if ($request->medications) {
                foreach ($request->medications as $medicationData) {
                    if (!empty($medicationData['name'])) {
                        Medication::create([
                            'appointment_id' => $appointment->id,
                            'medication_name' => $medicationData['name'],
                            'type' => $medicationData['type'] ?? null,
                            'dosage' => $medicationData['dosage'] ?? null,
                            'duration' => $medicationData['duration'] ?? null,
                            'instructions' => $medicationData['instructions'] ?? null, // Fixed: was 'instruction', should be 'instructions'
                        ]);
                    }
                }
            }
            
            // Update the appointment status to 'completed'
            $appointment->update([
                'status' => 'completed',
                'completed_at' => now(),
                'end_reason' => 'Session completed by doctor'
            ]);
            
            // Update the consultation end_time if there's a linked consultation
            $payment = $appointment->payment;
            if ($payment && $payment->consultation_id) {
                $consultation = \App\Models\Consultation::find($payment->consultation_id);
                if ($consultation) {
                    $consultation->update([
                        'end_time' => now(),
                        'status' => 'completed'
                    ]);
                }
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
            ->findOrFail($patientId);
            
        // Get all completed appointments for this patient with this doctor, ordered by date
        $completedAppointments = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->with(['doctor', 'patient', 'appointmentReason', 'vitals', 'clinicalNote', 'medications', 'appointmentDetail'])
            ->orderBy('appointment_time', 'desc')
            ->get();
            
        // Get total visit count for this patient with this doctor
        $totalVisits = Appointment::where('patient_id', $patient->id)
            ->where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->count();
            
        // If no completed appointments, redirect back
        if ($completedAppointments->isEmpty()) {
            return redirect()->back()->with('error', 'No completed appointments found for this patient.');
        }
        
        // Get the most recent completed appointment as the current one
        $currentAppointment = $completedAppointments->first();
        
        // Get appointment detail for the current appointment
        $currentAppointmentDetail = AppointmentDetail::firstOrCreate([
            'appointment_id' => $currentAppointment->id
        ]);
        
        // Load related data
        $currentAppointment->load(['vitals', 'clinicalNote', 'medications', 'doctor', 'patient', 'appointmentReason', 'appointmentDetail']);
        
        // Prepare appointments data for JavaScript
        $appointmentsData = [];
        foreach ($completedAppointments as $appointment) {
            // Ensure appointment detail exists
            $appointmentDetail = AppointmentDetail::firstOrCreate([
                'appointment_id' => $appointment->id
            ]);
            
            // Load all related data
            $appointment->load(['vitals', 'clinicalNote', 'medications', 'doctor', 'patient', 'appointmentReason', 'appointmentDetail']);
            
            $appointmentsData[] = [
                'id' => $appointment->id,
                'patient_name' => $appointment->patient->name ?? 'Unknown Patient',
                'patient_email' => $appointment->patient->email ?? '',
                'patient_phone' => $appointment->patient->phone ?? '',
                'doctor_name' => $appointment->doctor->name ?? 'Unknown Doctor',
                'appointment_reason' => $appointment->appointmentReason->name ?? $appointment->type ?? 'General Visit',
                'status' => $appointment->status,
                'consultation_fee' => $appointment->consultation_fee,
                'appointment_time' => $appointment->appointment_time,
                'clinic_location' => $appointment->clinic_location,
                'location' => $appointment->location,
                'visit_type' => $appointment->visit_type,
                'blood_group' => $appointmentDetail->blood_group,
                'clinical_notes' => $appointment->clinicalNote->note_text ?? '',
                'skin_allergy' => $appointment->clinicalNote->skin_allergy ?? '',
                'advice' => $appointmentDetail->advice,
                'follow_up_date' => $appointmentDetail->follow_up_date ? $appointmentDetail->follow_up_date->format('Y-m-d') : null,
                'follow_up_time' => $appointmentDetail->follow_up_time ? $appointmentDetail->follow_up_time->format('H:i') : null,
                'temperature' => $appointment->vitals->temperature ?? '',
                'pulse' => $appointment->vitals->pulse ?? '',
                'respiratory_rate' => $appointment->vitals->respiratory_rate ?? '',
                'spo2' => $appointment->vitals->spo2 ?? '',
                'height' => $appointment->vitals->height ?? '',
                'weight' => $appointment->vitals->weight ?? '',
                'waist' => $appointment->vitals->waist ?? '',
                'bsa' => $appointment->vitals->bsa ?? '',
                'bmi' => $appointment->vitals->bmi ?? '',
                'complaints' => $appointmentDetail->complaints ?? [],
                'diagnosis' => $appointmentDetail->diagnosis ?? [],
                'lab_tests' => $appointmentDetail->lab_tests ?? [],
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
}
