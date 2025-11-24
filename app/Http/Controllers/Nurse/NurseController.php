<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Vitals;
use App\Models\AppointmentDetail;
use App\Models\ClinicalNote;
use App\Models\Service;
use App\Models\Clinic;
use App\Models\Consultation;
use App\Models\Payment;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\DB;
use App\Services\AppointmentQueryService;
use App\Services\PaymentService;
use App\Services\AppointmentBookingService;

class NurseController extends Controller
{
    protected $appointmentQueryService;
    protected $paymentService;
    protected $appointmentBookingService;

    public function __construct(
        AppointmentQueryService $appointmentQueryService, 
        PaymentService $paymentService,
        AppointmentBookingService $appointmentBookingService
    ) {
        $this->appointmentQueryService = $appointmentQueryService;
        $this->paymentService = $paymentService;
        $this->appointmentBookingService = $appointmentBookingService;
    }
    
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD FUNCTIONS
    |--------------------------------------------------------------------------
    */
    
    public function dashboard()
    {
        $nurseId = Auth::user()->id;
        $cacheTime = 3600;
        
        $patientsWaitingCount = Cache::remember("nurse_{$nurseId}_patients_waiting", $cacheTime, function () {
            return Appointment::whereDate('appointment_time', Carbon::today())
                            ->where('status', 'confirmed') 
                            ->count();
        });

        $doctorsAvailableCount = Cache::remember("nurse_{$nurseId}_doctors_available", 60, function () {
            $now = Carbon::now();
            $currentDay = strtolower($now->format('l'));
            $currentTime = $now->format('H:i:s');
            $currentDate = $now->format('Y-m-d');

            return User::where('role', 'doctor')
                ->where('status', 'active')
                ->whereHas('doctorProfile', function ($query) {
                    $query->where('live_status', 'Available');
                })
                ->whereHas('doctorProfile.schedules', function ($query) use ($currentDay, $currentTime, $currentDate) {
                    $query->where('day_of_week', $currentDay)
                          ->where('start_date', '<=', $currentDate)
                          ->where('end_date', '>=', $currentDate)
                          ->where('start_time', '<=', $currentTime)
                          ->where('end_time', '>=', $currentTime);
                })
                ->count();
        });

        $patientsWithDoctorCount = Cache::remember("nurse_{$nurseId}_patients_with_doctor", $cacheTime, function () {
            return Appointment::whereDate('appointment_time', Carbon::today())
                                ->where('status', 'in_progress')
                                ->count();
        });
        $doctorsOnDutyCount = Cache::remember("nurse_{$nurseId}_doctors_on_duty", $cacheTime, function () {
            return User::where('role', 'doctor')
                          ->where('status', 'active')
                          ->count();
        });

        $doctors = $this->getDoctorStatusData(); 
        $patientQueue = $this->getPatientQueueData(); 

        $allTodaysAppointments = Cache::remember("nurse_{$nurseId}_all_today_appts", $cacheTime, function () {
            return Appointment::whereDate('appointment_time', Carbon::today())
                                ->with('patient', 'doctor')
                                ->orderBy('appointment_time', 'asc')
                                ->get();
        });

        $notifications = $this->getUnreadNotifications();
        $notificationCount = $this->getNotificationCount();
        $requestCount = 0; 

        return view('nurse.dashboard', [
            'patientsWaitingCount' => $patientsWaitingCount,
            'doctorsAvailableCount' => $doctorsAvailableCount,
            'patientsWithDoctorCount' => $patientsWithDoctorCount,
            'doctorsOnDutyCount' => $doctorsOnDutyCount,
            'doctors' => $doctors,
            'patientQueue' => $patientQueue,
            'allTodaysAppointments' => $allTodaysAppointments,
            'notifications' => $notifications,
            'notificationCount' => $notificationCount,
            'requestCount' => $requestCount,
        ]);
    }

    private function getUnreadNotifications()
    {
        $userId = Auth::id();
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }
    
    private function getNotificationCount()
    {
        $userId = Auth::id();
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }
    
    public function clearDashboardCache()
    {
        Cache::flush();
        return redirect()->route('nurse.dashboard')->with('success', 'Dashboard has been refreshed!');
    }

    private function getPatientQueueData()
    {
        $nurseId = Auth::id();
        $cacheTime = 60; 
        
        return Cache::remember("nurse_{$nurseId}_patient_queue", $cacheTime, function () {
            return Appointment::whereDate('appointment_time', Carbon::today())
                                    ->whereIn('status', ['checked_in', 'in_progress'])
                                    ->with('patient', 'doctor') 
                                    ->orderBy('appointment_time', 'asc')
                                    ->get();
        });
    }

    private function getDoctorStatusData()
    {
        $nurseId = Auth::id();
        $cacheTime = 60; 

        $doctors = Cache::remember("nurse_{$nurseId}_doctors_status", $cacheTime, function () {
            return User::where('role', 'doctor')
                            ->with('doctorProfile') 
                            ->where('status', 'active') 
                            ->orderBy('name', 'asc')
                            ->get();
        });

        $now = Carbon::now();
        $currentDay = strtolower($now->format('l')); 
        $currentTime = $now->format('H:i:s');
        $currentDate = $now->format('Y-m-d');

        foreach ($doctors as $doctor) {
            $manualStatus = $doctor->doctorProfile->live_status ?? 'Available';
            if ($manualStatus == 'In Appointment') {
                $doctor->real_time_status = 'In Appointment';
                continue; 
            }

            $isScheduled = false;
            if ($doctor->doctorProfile) {
                $isScheduled = $doctor->doctorProfile->schedules()
                    ->where('day_of_week', $currentDay)
                    ->where('start_date', '<=', $currentDate)
                    ->where('end_date', '>=', $currentDate)
                    ->where('start_time', '<=', $currentTime)
                    ->where('end_time', '>=', $currentTime)
                    ->exists();
            }

            if ($isScheduled) {
                $doctor->real_time_status = 'Available';
            } else {
                $doctor->real_time_status = 'Unavailable';
            }
        }
        return $doctors;
    }

    public function ajaxGetQueue()
    {
        $patientQueue = $this->getPatientQueueData();
        return view('nurse._queue_table', compact('patientQueue'));
    }

    public function ajaxGetDoctorStatus()
    {
        $doctors = $this->getDoctorStatusData();
        return view('nurse._doctor_list', compact('doctors'));
    }
    
    /*
    |--------------------------------------------------------------------------
    | VITALS & PATIENTS
    |--------------------------------------------------------------------------
    */

    public function saveVitals(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'blood_pressure' => 'nullable|string|max:20',
            'temperature' => 'nullable|string|max:10',
            'pulse' => 'nullable|string|max:10',
            'height' => 'nullable|string|max:10',
            'weight' => 'nullable|string|max:10',
            'spo2' => 'nullable|string|max:10',
            'blood_group' => 'nullable|string|max:10',
            'nurse_note' => 'nullable|string|max:1000',
        ]);
        
        try {
            $nurseId = Auth::id();
            
            Vitals::updateOrCreate(
                ['appointment_id' => $appointment->id],
                [
                    'doctor_id' => $nurseId, 
                    'blood_pressure' => $request->input('blood_pressure'),
                    'temperature' => $request->input('temperature'),
                    'pulse' => $request->input('pulse'),
                    'height' => $request->input('height'),
                    'weight' => $request->input('weight'),
                    'spo2' => $request->input('spo2'),
                ]
            );
            
            $detail = AppointmentDetail::firstOrCreate(
                ['appointment_id' => $appointment->id]
            );
            if ($request->filled('blood_group')) {
                $detail->blood_group = $request->input('blood_group');
            }
            $detail->save();

            if ($request->filled('nurse_note')) {
                ClinicalNote::updateOrCreate(
                    ['appointment_id' => $appointment->id],
                    [
                        'doctor_id' => $appointment->doctor_id,
                        'note_text' => $request->input('nurse_note')
                    ]
                );
            }
            
            if ($appointment->type == 'in_person' && $appointment->status == 'checked_in') {
                $appointment->status = 'vitals_taken'; 
                $appointment->save();

                $patientName = $appointment->patient->name ?? 'a patient';
                event(new \App\Events\DoctorAlert($appointment->doctor_id, "Vitals saved for {$patientName}. The patient is ready to be seen."));
            }

            Cache::flush();
            
            return redirect()->route('nurse.dashboard')->with('success', 'Vitals for '. $appointment->patient->name . ' saved successfully!');

        } catch (\Exception $e) {
            Log::error('Error saving vitals: ' . $e->getMessage());
            return redirect()->route('nurse.dashboard')->with('error', 'Error saving vitals. Please try again.');
        }
    }
    
    public function patientsIndex(Request $request)
    {
        $search = $request->get('search');
        
        $patients = User::where('role', 'patient')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%")
                      ->orWhere('user_id', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('name', 'asc')
            ->paginate(15);
        
        return view('nurse.patients.index', compact('patients'));
    }
    
    /*
    |--------------------------------------------------------------------------
    | BOOK APPOINTMENT FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function bookAppointment(Request $request)
    {
        $services = Service::with('activeTimePricings')->select('id', 'service_name', 'price_amount', 'price_currency', 'default_duration')->get();
        $patientData = [];

        if ($request->has('patient_id')) {
            $patient = User::where('user_id', $request->patient_id)->where('role', 'patient')->first();
            if ($patient) {
                $patientData = [
                    'user_id' => $patient->user_id,
                    'name' => $patient->name,
                    'email' => $patient->email,
                ];
            }
        }
        return view('nurse.book-appointment', compact('services', 'patientData'));
    }

    public function storeAppointment(Request $request)
    {
        // 1. Format the date
        $appointmentDate = $request->input('appointment_date');
        $formattedDate = null;
        if ($appointmentDate) {
            try {
                $dateObj = Carbon::createFromFormat('l d F Y - H:i', $appointmentDate);
                $formattedDate = $dateObj->format('Y-m-d H:i:s');
            } catch (\Exception $e) { /* validation handles it */ }
        }
        $request->merge(['appointment_date' => $formattedDate]);

        // 2. Validate the request
        $validatedData = $request->validate([
            'patient_id' => 'required|string|exists:users,user_id,role,patient',
            'appointment_date' => 'required|date|after:now',
            'clinic_id' => 'required|string',
            'doctor_id' => 'required|integer|exists:users,id,role,doctor',
            'service_id' => 'required|integer|exists:hospital_services,id',
            'service_duration' => 'required|integer|min:5',
            'service_price' => 'required|numeric|min:0', 
            'reason' => 'nullable|string|max:255',
            'payment_method' => 'required|string|in:cash,paystack',
        ]);
        
        try {
            $paymentMethod = ($validatedData['payment_method'] == 'cash') ? 'cash_in_clinic' : 'card_online';

            // Delegate to Booking Service
            $result = $this->appointmentBookingService->createAppointment($validatedData, $paymentMethod);
            
            Cache::flush(); 

            if ($paymentMethod === 'cash_in_clinic') {
                return redirect()->route('nurse.payments.pending.public', [
                    'reference' => $result['payment']->reference
                ])->with('success', 'Appointment booked! Payment is pending cash verification.');
            } else {
                return redirect()->route('nurse.appointment.payment.initialize', [
                    'service_id' => $result['service']->id,
                    'patient_id' => $result['consultation']->patient_id,
                    'consultation_id' => $result['consultation']->id,
                    'payment_id' => $result['payment']->id
                ])->with('info', 'Please complete payment to confirm your appointment.');
            }

        } catch (\Exception $e) {
            Log::error("[NurseController] Failed to store appointment: " . $e->getMessage());
            return redirect()->back()->with('error', 'There was an error creating the appointment. ' . $e->getMessage())->withInput();
        }
    }

    public function getPatientInfo(Request $request)
    {
        $patient = User::where('user_id', $request->patient_id)->where('role', 'patient')->first();
        if ($patient) {
            return response()->json(['patient' => $patient]);
        }
        return response()->json(['error' => 'Patient not found'], 404);
    }

    public function searchPatients(Request $request)
    {
        $patients = User::where('name', 'LIKE', '%' . $request->search . '%')
                        ->where('role', 'patient')
                        ->limit(10)
                        ->get(['id', 'user_id', 'name', 'email']);
        return response()->json(['patients' => $patients]);
    }

    public function storeWalkInPatient(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'nullable|email|max:255|unique:users,email',
        ]);

        $patient = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make(Str::random(10)),
            'role' => 'patient',
            'user_id' => 'PID' . (User::max('id') + 1),
            'status' => 'active', 
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Walk-in patient registered successfully!',
            'patient_id' => $patient->user_id,
        ]);
    }
    
    public function getAvailableLocations(Request $request)
    {
        return $this->appointmentQueryService->getAvailableLocations($request->input('date'));
    }
    
    public function getAvailableDoctors(Request $request)
    {
        return $this->appointmentQueryService->getAvailableDoctors(
            $request->input('date'),
            $request->input('clinic_id'),
            (int)$request->input('duration', 30)
        );
    } 

    public function getServiceTimePricing(Request $request)
    {
        $service = Service::find($request->service_id);
        if (!$service) {
            return response()->json(['error' => 'Service not found'], 404);
        }
        $duration = $request->duration;
        $basePrice = $service->price_amount;
        $baseDuration = $service->default_duration ?: 30; 
        
        $ratePerMinute = $basePrice / $baseDuration;
        $finalFee = $duration * $ratePerMinute;
        $minFee = 15 * $ratePerMinute;
        $finalFee = max($finalFee, $minFee);

        return response()->json([
            'price' => round($finalFee),
            'formatted_price' => '₦' . number_format(round($finalFee)),
        ]);
    }
    
    /*
    |--------------------------------------------------------------------------
    | PAYMENT FUNCTIONS (UPDATED FOR SHARED SERVICE)
    |--------------------------------------------------------------------------
    */
    
    public function paymentIndex()
    {
        $payments = Payment::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('nurse.payments.index', compact('payments'));
    }

    public function paymentCreate()
    {
        $patients = User::where('role', 'patient')->get();
        return view('nurse.payments.create', compact('patients'));
    }

    public function paymentStore(Request $request)
    {
        // Manual payments (Cash, etc.)
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string',
            'status' => 'required|string',
            'reference' => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);
        
        Payment::create($data);
        return redirect()->route('nurse.payments.index')->with('success', 'Payment created successfully.');
    }

    public function showAppointmentPayment(Request $request)
    {
        $consultationId = $request->input('consultation_id');
        $paymentId = $request->input('payment_id');
        
        $consultation = Consultation::findOrFail($consultationId);
        $payment = $paymentId ? Payment::find($paymentId) : null;
        $patient = User::findOrFail($consultation->patient_id);
        $service = Service::where('service_name', $consultation->service_type)->first();
        
        $publicKey = config('services.paystack.public_key');
        
        return view('nurse.appointment-payment', compact('consultation', 'payment', 'patient', 'publicKey', 'service'));
    }
    
    /**
     * Initialize Paystack using Shared Service
     */
    public function initializePaystack(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'amount' => 'required|numeric|min:1', // Changed from min:100 to min:1
            'consultation_id' => 'required|exists:consultations,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 400); 
        }

        $consultation = Consultation::findOrFail($request->consultation_id);

        // Metadata tells the system a NURSE did this
        $metadata = [
            'consultation_id' => $consultation->id,
            'patient_id'      => $consultation->patient_id,
            'clinic_id'       => Auth::user()->clinic_id ?? 1,
            'role_initiator'  => 'nurse'
        ];

        // Call Shared Service
        $result = $this->paymentService->initializePaymentTransaction(
            $request->email, 
            $request->amount, 
            $metadata
        );

        if (isset($result['status']) && $result['status'] === true) {
            // Update the existing payment record with metadata
            $payment = Payment::where('consultation_id', $consultation->id)->first();
            if ($payment) {
                $payment->update(['metadata' => $metadata]);
            }
            
            // Return only the reference, not the authorization URL
            return response()->json([
                'status' => true,
                'reference' => $result['data']['reference']
            ]);
        }
        
        return response()->json(['status' => false, 'message' => 'Payment initialization failed.'], 500);
    }
    
    // --- Payment Status Pages ---
    // Admin/PaymentController redirects here if Role == Nurse
    
    public function paymentSuccess(Request $request)
    {
        $reference = $request->query('reference');
        $payment = $reference ? Payment::where('reference', $reference)->first() : null;
        return view('nurse.payments.success', compact('payment'));
    }

    public function paymentFailed(Request $request)
    {
        $reference = $request->query('reference');
        return view('nurse.payments.failed', compact('reference'));
    }

    public function paymentPending(Request $request)
    {
        $reference = $request->query('reference');
        $payment = $reference ? Payment::where('reference', $reference)->first() : null;
        return view('nurse.payments.pending', compact('payment'));
    }
    
    // CRUD for Payments (View/Edit/Delete)
    public function paymentShow(Payment $payment) {
        $payment->load('user', 'consultation.doctor', 'appointment.doctor');
        return view('nurse.payments.show', compact('payment'));
    }
    public function paymentEdit(Payment $payment) {
        $patients = User::where('role', 'patient')->get();
        return view('nurse.payments.edit', compact('payment', 'patients'));
    }
    public function paymentUpdate(Request $request, Payment $payment) {
        $data = $request->validate([ 'amount' => 'required|numeric' ]); // Simplified for brevity
        $payment->update($data);
        return redirect()->route('nurse.payments.index')->with('success', 'Updated.');
    }
    public function paymentDestroy(Payment $payment) {
        $payment->delete();
        return redirect()->route('nurse.payments.index')->with('success', 'Deleted.');
    }
    public function paymentInvoice(Payment $payment) {
        $payment->load('user', 'consultation.doctor');
        return view('nurse.payments.invoice', compact('payment'));
    }
}