<?php

namespace App\Http\Controllers\Admin;

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
        
        return view('admin.book-appointment', compact('patientData', 'services'));
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
     * Get available doctors for a specific date.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAvailableDoctors(Request $request)
    {
        $date = $request->input('date');
        
        // Log the incoming request
        Log::info('Fetching available doctors for date: ' . $date);
        
        try {
            // Parse the date to get the day of week
            // The datetimepicker format is 'dddd DD MMMM YYYY - HH:mm'
            // Handle potential variations in the date format
            if (strpos($date, ' - ') !== false) {
                $appointmentDate = Carbon::createFromFormat('l d F Y - H:i', $date);
            } else {
                // Try alternative format if the above fails
                $appointmentDate = Carbon::parse($date);
            }
            $dayOfWeek = $appointmentDate->format('l'); // e.g., 'Monday', 'Tuesday', etc.
            
            // Log the day of week
            Log::info('Day of week: ' . $dayOfWeek);
        } catch (\Exception $e) {
            Log::error('Date parsing error: ' . $e->getMessage());
            Log::error('Date string received: ' . $date);
            return response()->json(['doctors' => []]);
        }
        
        // Get doctors with their availability
        $doctors = Doctor::with('user')
            ->where('status', 'Verified')
            ->get();
            
        // Log the number of doctors found
        Log::info('Total verified doctors found: ' . $doctors->count());
        
        $filteredDoctors = $doctors->filter(function ($doctor) use ($dayOfWeek, $appointmentDate) {
            // Log doctor info
            Log::info('Checking doctor: ' . ($doctor->user ? $doctor->user->name : 'Unknown') . ' (ID: ' . $doctor->id . ')');
            
            // Check if doctor has availability data
            if (!$doctor->availability) {
                Log::info('Doctor has no availability data, assuming available');
                return true; // If no availability set, assume available
            }
            
            $availability = $doctor->availability; // This is already an array due to casting
            
            // Log availability data
            Log::info('Doctor availability: ' . json_encode($availability));
            
            // Check if doctor is available on this day
            if (isset($availability[$dayOfWeek])) {
                $dayAvailability = $availability[$dayOfWeek];
                
                // Log day availability
                Log::info('Day availability for ' . $dayOfWeek . ': ' . json_encode($dayAvailability));
                
                // Check if time slots are available
                if (isset($dayAvailability['slots']) && !empty($dayAvailability['slots'])) {
                    Log::info('Doctor has available slots');
                    return true;
                }
                
                // If it's a full day availability
                if (isset($dayAvailability['available']) && $dayAvailability['available']) {
                    Log::info('Doctor is available for full day');
                    return true;
                }
            }
            
            Log::info('Doctor is not available on ' . $dayOfWeek);
            return false;
        })
        ->map(function ($doctor) {
            return [
                'id' => $doctor->user->id,
                'name' => $doctor->user->name
            ];
        });
        
        // Log the number of filtered doctors
        Log::info('Filtered doctors count: ' . $filteredDoctors->count());
        
        return response()->json(['doctors' => array_values($filteredDoctors->toArray())]);
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