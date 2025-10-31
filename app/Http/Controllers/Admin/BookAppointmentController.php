<?php

namespace App\Http\Controllers\Admin;

use App\Models\DoctorSchedule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\ServiceTimePricing;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Clinic;
use Illuminate\Support\Facades\Cache; // <-- ADD THIS "WHISTLEBLOWER" IMPORT

class BookAppointmentController extends Controller
{
    /**
     * Show the book appointment form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check if patient data is passed in the request
        $patientData = null;
        if ($request->has('patient_id')) {
            $patientData = [
                'user_id' => $request->patient_id,
                'name' => $request->patient_name ?? '',
                'email' => $request->patient_email ?? ''
            ];
        }
        
        // Fetch all active services for the dropdown
        $services = Service::with('activeTimePricings')->get();
        $clinics = Clinic::where('is_physical', 1)->get(); 
        
        return view('admin.book-appointment', compact('patientData', 'services', 'clinics'));
    }

    /**
     * Get patient information by patient ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPatientInfo(Request $request)
    {
        $patientId = $request->input('patient_id');
        
        $patient = User::where('user_id', $patientId)
            ->where('role', 'patient')
            ->first();
            
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }
        
        return response()->json(['patient' => $patient]);
    }

    /**
     * Get available doctors based on date, time, location, AND check for conflicts.
     */
    public function getAvailableDoctors(Request $request)
    {
        Log::info("=== GetAvailableDoctors Start (with conflict check) ===");
        // 1. Get data from the form
        $dateTimeString = $request->input('date');
        $clinicId = $request->input('clinic_id');
        $duration = (int)$request->input('duration', 30); // Default to 30 mins if not sent

        Log::info("Received: date='{$dateTimeString}', clinic='{$clinicId}', duration='{$duration}'");

        if (!$dateTimeString || !$clinicId) {
            Log::warning("Missing date/time string or clinic ID.");
            return response()->json(['doctors' => []]);
        }

        try {
            // 2. Convert start time to Carbon object
            $appointmentStart = Carbon::createFromFormat('l d F Y - H:i', $dateTimeString);
            // Calculate potential appointment end time
            $appointmentEnd = $appointmentStart->copy()->addMinutes($duration);
             Log::info("Calculated Appointment Slot: Start={$appointmentStart->toDateTimeString()}, End={$appointmentEnd->toDateTimeString()}");

        } catch (\Exception $e) {
            Log::error("!!! Date parsing error: " . $e->getMessage() . " | Input: " . $dateTimeString);
            return response()->json(['doctors' => [], 'error' => 'Invalid date format.']);
        }

        // 3. Get pieces needed for schedule check
        $dayOfWeek = strtolower($appointmentStart->format('l'));
        $startTime = $appointmentStart->format('H:i:s');
        $date = $appointmentStart->format('Y-m-d');
        Log::info("Checking Schedule Rulebook for: Day={$dayOfWeek}, Date={$date}, Time={$startTime}, Location={$clinicId}");

        // 4. Find doctor IDs matching the schedule rules
        $scheduledDoctorIds = DoctorSchedule::where('location', $clinicId)
            ->where('day_of_week', $dayOfWeek)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where(DB::raw('CAST(start_time AS TIME)'), '<=', $startTime)
            ->where(DB::raw('CAST(end_time AS TIME)'), '>', $startTime) // Check start time fits schedule
             // --- ALSO CHECK END TIME FITS SCHEDULE ---
             // Ensures the *entire* duration fits within the doctor's scheduled block
            ->where(DB::raw('CAST(end_time AS TIME)'), '>=', $appointmentEnd->format('H:i:s'))
             // --- END END TIME CHECK ---
            ->pluck('doctor_id')
            ->unique();
        Log::info("Found " . $scheduledDoctorIds->count() . " doctor IDs matching schedule rules.");

        if ($scheduledDoctorIds->isEmpty()) {
             Log::info("No doctors scheduled at this time/location. Returning empty list.");
             Log::info("=== GetAvailableDoctors End ===");
             return response()->json(['doctors' => []]);
        }

        // 5. Filter those doctors by verified status (User and Profile)
        $verifiedDoctorIds = User::whereIn('id', $scheduledDoctorIds)
                      ->where('role', 'doctor')
                      ->where('status', 'active') // Check User status
                      ->whereHas('doctorProfile', function ($query) { // Check doctors_new status
                           $query->where('status', 'verified'); // Use lowercase 'verified'
                      })
                      ->pluck('id'); // Get just the IDs
        Log::info("Found " . $verifiedDoctorIds->count() . " verified doctors matching schedule.");

         if ($verifiedDoctorIds->isEmpty()) {
              Log::info("No *verified* doctors match the schedule. Returning empty list.");
              Log::info("=== GetAvailableDoctors End ===");
              return response()->json(['doctors' => []]);
         }


        // --- 6. CORRECTED AGAIN: Check for Conflicting Consultations ---
             Log::info("Checking for conflicts for doctor IDs: " . json_encode($verifiedDoctorIds->toArray())); // Log the IDs going into the check
             $conflictingDoctorIds = \App\Models\Consultation::whereIn('doctor_id', $verifiedDoctorIds) // *** Use Consultation model ***
                 // Look for consultations that are NOT completed, missed, or cancelled
                 ->whereNotIn('status', ['completed', 'missed', 'cancelled'])
                 // Check for overlap:
                 // (ExistingStart < ProposedEnd) AND (ExistingEnd > ProposedStart)
                 ->where(function ($query) use ($appointmentStart, $appointmentEnd) {
                     $query->where('start_time', '<', $appointmentEnd) // *** Use Consultation's start_time ***
                           ->where(DB::raw('DATE_ADD(start_time, INTERVAL duration_minutes MINUTE)'), '>', $appointmentStart); // *** Use Consultation's start_time AND duration_minutes ***
                 })
                 ->pluck('doctor_id') // Get IDs of doctors WITH conflicts
                 ->unique();
             Log::info("Found " . $conflictingDoctorIds->count() . " doctors with conflicting consultations: " . json_encode($conflictingDoctorIds->toArray()));
             // --- END CONFLICT CHECK ---

             // 7. Get the IDs of doctors who are verified AND have NO conflicts
             // Use collect() helper to make diff() easier
             $trulyAvailableDoctorIds = collect($verifiedDoctorIds)->diff($conflictingDoctorIds);
             Log::info("Final available (verified, no conflict) doctor IDs: " . json_encode($trulyAvailableDoctorIds->toArray())); // Use toArray() for logging

             // 8. Get the details for the final list (Users table)
             $doctors = User::whereIn('id', $trulyAvailableDoctorIds)
                           ->select('id', 'name')
                           ->get();
             Log::info("Returning " . $doctors->count() . " final available doctors.");

             // 9. Send the list back
             Log::info("=== GetAvailableDoctors End ===");
             return response()->json(['doctors' => $doctors]);
    } // Make sure this closing brace matches the function definition

    /**
     * Find available LOCATIONS based on a selected date and time.
     * (With added logging and CORRECTED status checks)
     */
    public function getAvailableLocations(Request $request)
    {
        // 1. Get the date/time string
        $dateTimeString = $request->input('date');
        Log::info("=== GetAvailableLocations Start ==="); // LOG START
        Log::info("Received date/time string: " . $dateTimeString); // LOG 1
        if (!$dateTimeString) {
            Log::warning("No date/time string received.");
            return response()->json(['locations' => []]);
        }

        try {
            // 2. Convert to Carbon object
            $selectedDateTime = Carbon::createFromFormat('l d F Y - H:i', $dateTimeString);
        } catch (\Exception $e) {
            Log::error("!!! Date parsing error: " . $e->getMessage() . " | Input: " . $dateTimeString);
            return response()->json(['locations' => [], 'error' => 'Invalid date format.']);
        }

        // 3. Get the pieces needed
        $dayOfWeek = strtolower($selectedDateTime->format('l'));
        $time = $selectedDateTime->format('H:i:s');
        $date = $selectedDateTime->format('Y-m-d');
        Log::info("Checking Rulebook for: Day={$dayOfWeek}, Date={$date}, Time={$time}"); // LOG 2

        // 4. Find matching schedules WITHOUT doctor check first
        $schedulesQuery = DoctorSchedule::where('day_of_week', $dayOfWeek)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where(DB::raw('CAST(start_time AS TIME)'), '<=', $time)
            ->where(DB::raw('CAST(end_time AS TIME)'), '>', $time);

        Log::debug("SQL for matching schedules (Time/Date only): " . $schedulesQuery->toSql(), $schedulesQuery->getBindings()); // LOG 3
        $schedulesFound = $schedulesQuery->get();
        Log::info("Found " . $schedulesFound->count() . " schedules matching time/date criteria."); // LOG 4

        if ($schedulesFound->isEmpty()) {
            Log::info("No schedules match time/date. Returning empty locations.");
            Log::info("=== GetAvailableLocations End ==="); // LOG END
            return response()->json(['locations' => []]);
        }

        // 5. NOW check the doctors for those schedules
        $verifiedDoctorIds = [];
        foreach ($schedulesFound as $schedule) {
            $doctorUser = $schedule->doctor; // Use the relationship

            // --- START DETAILED DOCTOR CHECK ---
            if (!$doctorUser) {
                Log::warning("Schedule ID {$schedule->id} has no linked user (doctor_id: {$schedule->doctor_id}). Skipping.");
                continue;
            }

            Log::info("Checking Doctor User ID: {$doctorUser->id}, Name: {$doctorUser->name}, Role: {$doctorUser->role}, User Status: '{$doctorUser->status}'");

            if ($doctorUser->role !== 'doctor') {
                 Log::info("--> User ID {$doctorUser->id} is not a doctor. Skipping.");
                 continue;
            }
            // *** CORRECTED STATUS CHECK FOR USER ***
            if ($doctorUser->status !== 'active') { // Use 'active' from users table
                 Log::info("--> User ID {$doctorUser->id} status is not 'active'. Skipping.");
                 continue;
            }

            // --- Check the Doctor Profile (doctors_new table) ---
            // !!! Using 'doctorProfile' based on your User model !!!
            $doctorProfile = $doctorUser->doctorProfile;

            if (!$doctorProfile) {
                Log::warning("--> User ID {$doctorUser->id} has no Doctor Profile (doctors_new record). Skipping.");
                continue;
            }

            Log::info("--> Checking Doctor Profile Status: '{$doctorProfile->status}'");
            // *** CORRECTED STATUS CHECK FOR DOCTOR PROFILE ***
            if ($doctorProfile->status !== 'verified') { // Use 'Verified' from doctors_new table
                Log::info("--> Doctor Profile status for User ID {$doctorUser->id} is not 'Verified'. Skipping.");
                continue;
            }
            // --- END DETAILED DOCTOR CHECK ---

            Log::info("===> Doctor User ID {$doctorUser->id} PASSED all checks. Adding to list.");
            $verifiedDoctorIds[] = $doctorUser->id; // Add the USER ID
        }
        // Make sure the list has unique IDs
        $uniqueVerifiedDoctorIds = array_unique($verifiedDoctorIds);
        Log::info("Unique Verified Doctor User IDs found: " . json_encode($uniqueVerifiedDoctorIds)); // LOG 5

        // 6. Filter the original schedules to only include those linked to verified doctors
        $finalSchedules = $schedulesFound->whereIn('doctor_id', $uniqueVerifiedDoctorIds);
        Log::info("Found " . $finalSchedules->count() . " schedules linked to verified doctors."); // LOG 6


        // 7. Get unique locations from the FINAL schedules
        $availableLocationIds = $finalSchedules->pluck('location')->unique()->values();
        Log::info("Unique location IDs from final schedules: " . json_encode($availableLocationIds)); // LOG 7

        // 8. Build the final list
        $locations = [];
        $clinicIds = [];
        foreach ($availableLocationIds as $locationId) {
            if ($locationId === 'virtual') {
                $locations[] = ['id' => 'virtual', 'name' => 'Virtual Session'];
            } else if (is_numeric($locationId)) {
                $clinicIds[] = (int)$locationId;
            }
        }

        if (!empty($clinicIds)) {
             Log::info("Fetching names for clinic IDs: " . json_encode($clinicIds)); // LOG 8
            $clinics = \App\Models\Clinic::whereIn('id', $clinicIds)->select('id', 'name')->get();
            foreach ($clinics as $clinic) {
                $locations[] = ['id' => $clinic->id, 'name' => $clinic->name];
            }
        }

        // Optional Sort
        usort($locations, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        Log::info("Returning final locations list: " . json_encode($locations)); // LOG 9
        Log::info("=== GetAvailableLocations End ==="); // LOG END
        return response()->json(['locations' => $locations]);
    }
        

    /**
     * Store a newly created appointment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // First, we need to convert the datetimepicker format to a standard date format
        $appointmentDate = $request->input('appointment_date');
        $formattedDate = null;
        
        if ($appointmentDate) {
            try {
                // Parse the datetimepicker format 'dddd DD MMMM YYYY - HH:mm'
                $dateObj = \Carbon\Carbon::createFromFormat('l d F Y - H:i', $appointmentDate);
                $formattedDate = $dateObj->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                // If parsing fails, we'll let the validation handle it
            }
        }
        
        // Replace the appointment_date in the request with the formatted version
        $request->merge(['appointment_date' => $formattedDate]);
        
        $request->validate([
            'patient_id' => 'required|exists:users,user_id', // Patient ID is required and must exist
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'service_id' => 'required|exists:hospital_services,id',
            'service_duration' => 'required|integer|in:30,40,60', // Validate duration
            'service_price' => 'required|numeric|min:0', // Validate price
            'reason' => 'nullable|string',
        ]);
        
        // Get the patient user ID
        $patient = User::where('user_id', $request->input('patient_id'))->first();
        
        // Get the service
        $service = Service::findOrFail($request->input('service_id'));
        
        // SECURITY: Do not trust the service_price from the form submission
        // Recalculate the price based on service and duration for data integrity
        $basePrice = $service->price_amount;
        $duration = $request->input('service_duration');
        
        // Define the price modifiers based on selected duration (minutes)
        // 30 mins: 1.0 (Base Price)
        // 40 mins: 1.25 (Base Price + 25%)
        // 60 mins: 1.50 (Base Price + 50%)
        $durationModifiers = [
            30 => 1.0,
            40 => 1.25,
            60 => 1.50
        ];
        
        $modifier = $durationModifiers[$duration] ?? 1.0;
        $calculatedFee = $basePrice * $modifier;
        
        // Create Consultation Record (initially scheduled but pending payment)
        $consultation = new \App\Models\Consultation();
        $consultation->patient_id = $patient->id;
        $consultation->doctor_id = $request->input('doctor_id');
        $consultation->location_id = 1; // Default to virtual channel
        $consultation->delivery_channel = 'virtual'; // Default to virtual
        $consultation->service_type = $service->service_name;
        $consultation->reason = $request->input('reason'); // Store the reason in the consultation
        $consultation->duration_minutes = $request->input('service_duration'); // Store the duration
        $consultation->fee = $calculatedFee; // Use our calculated fee, not the submitted one
        $consultation->status = 'scheduled'; // Use 'scheduled' as it's a valid enum value
        $consultation->start_time = $request->input('appointment_date');
        $consultation->save();
        
        // Create Payment Record (initially pending)
        $payment = new \App\Models\Payment();
        $payment->user_id = $patient->id;
        $payment->consultation_id = $consultation->id;
        $payment->clinic_id = 1; // Default to virtual channel
        $payment->amount = $calculatedFee; // Use our calculated fee, not the submitted one
    
        // Set payment method and status for pending payment
        $payment->method = 'card_online'; // Will be updated after successful payment
        $payment->status = 'pending_cash_verification'; // Pending until payment is verified
        
        $payment->reference = 'CONS-' . $consultation->id . '-' . time();
        $payment->transaction_date = now();
        $payment->save();
        
        // DO NOT create appointment record yet - only create after successful payment
        // DO NOT create notification yet - only create after successful payment
        
        // Redirect to payment page
        return redirect()->route('admin.appointment.payment.initialize', [
            'service_id' => $service->id,
            'patient_id' => $patient->id,
            'consultation_id' => $consultation->id,
            'payment_id' => $payment->id
        ])->with('info', 'Please complete payment to confirm your appointment.');
    }
    
    /**
     * Show the doctor availability management form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAvailabilityForm()
    {
        $doctors = Doctor::with('user')->where('status', 'Verified')->get();
        return view('admin.doctor-availability', compact('doctors'));
    }
    
    /**
     * Update doctor availability.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAvailability(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors_new,id',
            'availability' => 'required|array',
        ]);
        
        $doctor = Doctor::findOrFail($request->input('doctor_id'));
        $doctor->availability = $request->input('availability');
        $doctor->save();
        
        return redirect()->back()->with('success', 'Doctor availability updated successfully!');
    }
    
    /**
     * Store a walk-in patient and redirect back to booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeWalkInPatient(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);
        
        // Create a new patient user
        $user = new User();
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->email = $request->input('email');
        $user->role = 'patient';
        $user->status = 'active';
        
        // Generate a unique patient ID
        $userId = 'PAT-' . time() . '-' . rand(1000, 9999);
        $user->user_id = $userId;
        
        // Set a default password (should be changed by patient later)
        $user->password = bcrypt('password123');
        
        $user->save();

        // --- THIS IS THE "WHISTLEBLOWER" ---
        // A new patient was created. Erase the "whiteboard" answers!
        Cache::forget("admin_stats_total_users");
        Cache::forget("admin_stats_new_registrations_7d");
        Cache::forget("admin_stats_new_patients_list");
        Cache::forget("admin_stats_prev_week_registrations");
        // --- END OF WHISTLEBLOWER ---
        
        return response()->json([
            'success' => true,
            'patient_id' => $userId,
            'message' => 'Patient registered successfully!'
        ]);
    }
    
    /**
     * Show payment page for appointment
     */
    public function showAppointmentPayment(Request $request)
    {
        $consultationId = $request->input('consultation_id');
        $paymentId = $request->input('payment_id');
        $serviceId = $request->input('service_id');
        $patientId = $request->input('patient_id');
        
        // Get consultation and payment details
        $consultation = \App\Models\Consultation::findOrFail($consultationId);
        $payment = \App\Models\Payment::findOrFail($paymentId);
        $patient = \App\Models\User::findOrFail($consultation->patient_id);
        $service = \App\Models\Service::findOrFail($serviceId ?? $consultation->service_type);
        
        // Get Paystack public key
        $publicKey = config('services.paystack.public_key');
        
        return view('admin.appointment-payment', compact('consultation', 'payment', 'patient', 'publicKey', 'service'));
    }
    
    /**
     * Search patients by name.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchPatients(Request $request)
    {
        $searchTerm = $request->input('search');
        
        if (strlen($searchTerm) < 2) {
            return response()->json(['patients' => []]);
        }
        
        $patients = User::where('role', 'patient')
            ->where('name', 'LIKE', '%' . $searchTerm . '%')
            ->select('id', 'user_id', 'name', 'email')
            ->limit(10)
            ->get();
            
        return response()->json(['patients' => $patients]);
    }
    
    /**
     * Get time-based pricing for a service.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getServiceTimePricing(Request $request)
    {
        $serviceId = $request->input('service_id');
        
        $service = Service::with('activeTimePricings')->findOrFail($serviceId);
        
        // Return the time pricings for this service
        return response()->json([
            'time_pricings' => $service->activeTimePricings,
            'default_price' => $service->price_amount,
            'default_duration' => $service->default_duration
        ]);
    }
}