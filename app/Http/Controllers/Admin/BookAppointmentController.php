<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\Clinic;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\AppointmentQueryService; 
use App\Services\AppointmentBookingService; 
use App\Traits\ManagesAdminCache;

class BookAppointmentController extends Controller
{
    use ManagesAdminCache;

    protected $appointmentQueryService;
    protected $appointmentBookingService;

    public function __construct(
        AppointmentQueryService $appointmentQueryService, 
        AppointmentBookingService $appointmentBookingService
    ) {
        $this->appointmentQueryService = $appointmentQueryService;
        $this->appointmentBookingService = $appointmentBookingService;
    }

    public function index(Request $request)
    {
        $patientData = null;
        if ($request->has('patient_id')) {
            $patientData = [
                'user_id' => $request->patient_id,
                'name' => $request->patient_name ?? '',
                'email' => $request->patient_email ?? ''
            ];
        }
        $services = Service::with('activeTimePricings')->select('id', 'service_name', 'price_amount', 'price_currency', 'default_duration')->get();
        $clinics = Clinic::where('is_physical', 1)->get(); 
        
        return view('admin.book-appointment', compact('patientData', 'services', 'clinics'));
    }

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

    public function getAvailableDoctors(Request $request)
    {
        return $this->appointmentQueryService->getAvailableDoctors(
            $request->input('date'),
            $request->input('clinic_id'),
            (int)$request->input('duration', 30)
        );
    } 

    public function getAvailableLocations(Request $request)
    {
        return $this->appointmentQueryService->getAvailableLocations(
            $request->input('date')
        );
    }
        
    public function store(Request $request)
    {
        // 1. Format the date from the picker
        $appointmentDate = $request->input('appointment_date');
        $formattedDate = null;
        if ($appointmentDate) {
            try {
                $dateObj = \Carbon\Carbon::createFromFormat('l d F Y - H:i', $appointmentDate);
                $formattedDate = $dateObj->format('Y-m-d H:i:s');
            } catch (\Exception $e) { /* validation handles it */ }
        }
        $request->merge(['appointment_date' => $formattedDate]);
        
        // 2. Validate the request
        $validatedData = $request->validate([
            'patient_id' => 'required|exists:users,user_id,role,patient', 
            'doctor_id' => 'required|exists:users,id,role,doctor',
            'appointment_date' => 'required|date|after:now',
            'service_id' => 'required|exists:hospital_services,id',
            'service_duration' => 'required|integer|min:1', 
            'service_price' => 'required|numeric|min:0', 
            'reason' => 'nullable|string',
            'clinic_id' => 'required|string',
        ]);
        
        try {
            // 3. Delegate to "Booker"
            $result = $this->appointmentBookingService->createAppointment(
                $validatedData,
                'card_online' // Admin initiated bookings usually proceed to payment gateway or pos
            );

            // 4. Redirect to the payment page
            $this->flushAdminStatsCache();
            
            return redirect()->route('admin.appointment.payment.initialize', [
                'service_id' => $result['service']->id,
                'patient_id' => $result['consultation']->patient_id,
                'consultation_id' => $result['consultation']->id,
                'payment_id' => $result['payment']->id
            ])->with('info', 'Please complete payment to confirm your appointment.');

        } catch (\Exception $e) {
            Log::error("[BookAppointmentController] Failed to store appointment: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating appointment: ' . $e->getMessage())->withInput();
        }
    }
    
    public function storeWalkInPatient(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'nullable|email|max:255|unique:users,email',
        ]);
        
        try {
            $user = new User();
            $user->name = $request->input('name');
            $user->phone = $request->input('phone');
            $user->email = $request->input('email');
            $user->save();

            return redirect()->route('admin.book.appointment')->with('info', 'Patient created successfully! You can now proceed to booking.');
        } catch (\Exception $e) {
            Log::error("[BookAppointmentController] Failed to store walk-in patient: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating patient: ' . $e->getMessage())->withInput();
        }
    }
    
    public function showAvailabilityForm()
    {
        $doctors = Doctor::with('user')->where('status', 'Verified')->get();
        return view('admin.doctor-availability', compact('doctors'));
    }
    
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
    
    public function showAppointmentPayment(Request $request)
    {
        $consultationId = $request->input('consultation_id');
        $paymentId = $request->input('payment_id');
        $serviceId = $request->input('service_id');
        
        $consultation = \App\Models\Consultation::findOrFail($consultationId);
        $payment = \App\Models\Payment::findOrFail($paymentId);
        $patient = \App\Models\User::findOrFail($consultation->patient_id);
        $service = \App\Models\Service::findOrFail($serviceId ?? $consultation->service_type);
        $publicKey = config('services.paystack.public_key');
        
        return view('admin.appointment-payment', compact('consultation', 'payment', 'patient', 'publicKey', 'service'));
    }
    
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
    
    public function getServiceTimePricing(Request $request)
    {
        $serviceId = $request->input('service_id');
        $service = Service::with('activeTimePricings')->findOrFail($serviceId);
        return response()->json([
            'time_pricings' => $service->activeTimePricings,
            'default_price' => $service->price_amount,
            'default_duration' => $service->default_duration
        ]);
    }
}