<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
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
        
        return view('admin.book-appointment', compact('patientData'));
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
            'patient_id' => 'required|exists:users,user_id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'service' => 'required|string',
            'reason' => 'nullable|string',
        ]);
        
        // Get the patient user ID
        $patient = User::where('user_id', $request->input('patient_id'))->first();
        
        // Get the service fee (hardcoded for now, but should come from a services table)
        $serviceFees = [
            'general_checkup' => 50.00,
            'dental_checkup' => 75.00,
            'full_body_checkup' => 150.00,
            'ent_checkup' => 60.00,
            'heart_checkup' => 120.00,
            'other' => 0.00
        ];
        
        $fee = $serviceFees[$request->input('service')] ?? 0.00;
        
        // Create Consultation Record
        $consultation = new \App\Models\Consultation();
        $consultation->patient_id = $patient->id;
        $consultation->doctor_id = $request->input('doctor_id');
        $consultation->location_id = 1; // Default to virtual channel
        $consultation->delivery_channel = 'virtual'; // Default to virtual
        $consultation->service_type = $request->input('service');
        $consultation->fee = $fee;
        $consultation->status = 'scheduled';
        $consultation->start_time = $request->input('appointment_date');
        $consultation->save();
        
        // Create Payment Record
        $payment = new \App\Models\Payment();
        $payment->user_id = $patient->id;
        $payment->consultation_id = $consultation->id;
        $payment->clinic_id = 1; // Default to virtual channel
        $payment->amount = $fee;
        
        // Set payment method and status
        $payment->method = 'card_online';
        $payment->status = 'paid'; // Changed from 'pending' to 'paid' as it's a valid enum value
        
        $payment->reference = 'CONS-' . $consultation->id . '-' . time();
        $payment->transaction_date = now();
        $payment->save();
        
        // Create legacy appointment record for backward compatibility
        $appointment = new Appointment();
        $appointment->patient_id = $patient->id;
        $appointment->doctor_id = $request->input('doctor_id');
        $appointment->appointment_time = $request->input('appointment_date');
        $appointment->notes = $request->input('reason');
        $appointment->type = 'telehealth'; // Assuming telehealth since we're creating a consultation
        $appointment->status = 'pending';
        $appointment->save();
        
        return redirect()->route('admin.book-appointment')->with('success', 'Appointment booked successfully! Consultation ID: ' . $consultation->id);
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
}