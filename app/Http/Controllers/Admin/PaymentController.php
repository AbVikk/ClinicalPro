<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Log; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache; 
use Illuminate\Support\Facades\DB; // Ensure DB is included for any raw queries/joins

class PaymentController extends Controller
{
    protected $paystackBaseUrl = 'https://api.paystack.co';

    /**
     * Display a listing of payments
     */
    public function index()
    {
        $payments = Payment::with(['user', 'appointment', 'consultation'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment
     */
    public function create()
    {
        $patients = User::where('role', 'patient')->get();
        $doctors = User::where('role', 'doctor')->get();
        $appointments = Appointment::with(['patient', 'doctor'])->get();
        
        return view('admin.payments.create', compact('patients', 'doctors', 'appointments'));
    }

    /**
     * Store a newly created payment in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|in:cash,cheque,credit_card,debit_card,netbanking,insurance,paystack,bank_transfer',
            'status' => 'required|in:pending,completed,failed,refunded,paid,pending_cash_verification',
            'reference' => 'nullable|string|max:255',
            'transaction_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Map form values to database values
        $methodMapping = [
            'credit_card' => 'card_online',
            'debit_card' => 'card_online',
            'cash' => 'cash_in_clinic',
            'cheque' => 'cash_in_clinic',
        ];
        
        $method = $request->input('method');
        
        if (isset($methodMapping[$method])) {
            $method = $methodMapping[$method];
        }

        // Map status values
        $statusMapping = [
            'completed' => 'paid',
            'pending' => 'pending_cash_verification',
        ];
        
        $status = $request->status;
        if (isset($statusMapping[$status])) {
            $status = $statusMapping[$status];
        }

        $payment = Payment::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'method' => $method,
            'status' => $status,
            'reference' => $request->reference,
            'transaction_date' => $request->transaction_date ?? now(),
            'clinic_id' => Auth::user()->clinic_id ?? 1, 
        ]);

        // --- WHISTLEBLOWER ---
        if ($status === 'paid') {
            Cache::forget("admin_stats_total_payments_month");
        }
        // --- END WHISTLEBLOWER ---

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment added successfully.');
    }

    /**
     * Display the specified payment
     */
    public function show(Payment $payment)
    {
        $payment->load(['user', 'appointment', 'consultation']);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment
     */
    public function edit(Payment $payment)
    {
        $patients = User::where('role', 'patient')->get();
        $doctors = User::where('role', 'doctor')->get();
        
        $methodMapping = [
            'card_online' => 'credit_card',
            'cash_in_clinic' => 'cash',
            'bank_transfer' => 'bank_transfer',
        ];
        
        $payment->method = $methodMapping[$payment->method] ?? $payment->method;
        
        return view('admin.payments.edit', compact('payment', 'patients', 'doctors'));
    }

    /**
     * Update the specified payment in storage
     */
    public function update(Request $request, Payment $payment)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|in:cash,cheque,credit_card,debit_card,netbanking,insurance,paystack,bank_transfer',
            'status' => 'required|in:pending,completed,failed,refunded,paid,pending_cash_verification',
            'reference' => 'nullable|string|max:255',
            'transaction_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $methodMapping = [
            'credit_card' => 'card_online',
            'debit_card' => 'card_online',
            'cash' => 'cash_in_clinic',
            'cheque' => 'cash_in_clinic',
        ];
        
        $method = $request->input('method');
        
        if (isset($methodMapping[$method])) {
            $method = $methodMapping[$method];
        }

        $statusMapping = [
            'completed' => 'paid',
            'pending' => 'pending_cash_verification',
        ];
        
        $status = $request->status;
        if (isset($statusMapping[$status])) {
            $status = $statusMapping[$status];
        }

        $payment->update([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'method' => $method,
            'status' => $status,
            'reference' => $request->reference,
            'transaction_date' => $request->transaction_date ?? $payment->transaction_date,
        ]);

        // --- WHISTLEBLOWER ---
        Cache::forget("admin_stats_total_payments_month");
        // --- END WHISTLEBLOWER ---

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified payment from storage
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        // --- WHISTLEBLOWER ---
        Cache::forget("admin_stats_total_payments_month");
        // --- END WHISTLEBLOWER ---

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    /**
     * Show invoice list for all payments
     */
    public function invoiceList()
    {
        $payments = Payment::with(['user', 'appointment.doctor'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.invoice', compact('payments'));
    }

    /**
     * Initialize Paystack payment
     */
    public function initializePaystack(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'amount' => 'required|numeric|min:100', 
            'metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $amountInKobo = $request->amount * 100;

        $paystack = new \Yabacon\Paystack(config('services.paystack.secret_key'));
        
        try {
            $tranx = $paystack->transaction->initialize([
                'amount' => $amountInKobo,
                'email' => $request->email,
                'callback_url' => route('admin.payments.paystack.callback'),
                'metadata' => $request->metadata ?? [],
            ]);

            return response()->json(['authorization_url' => $tranx->data->authorization_url]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment initialization failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Handle Paystack callback
     */
    public function handlePaystackCallback(Request $request)
    {
        $paystack = new \Yabacon\Paystack(config('services.paystack.secret_key'));
        
        try {
            $tranx = $paystack->transaction->verify([
                'reference' => $request->reference,
            ]);

            if ('success' === $tranx->data->status) {
                $payment = Payment::updateOrCreate(
                    ['reference' => $request->reference],
                    [
                        'user_id' => Auth::id(),
                        'amount' => $tranx->data->amount / 100, 
                        'method' => 'card_online', 
                        'status' => 'paid', 
                        'transaction_date' => now(),
                        'clinic_id' => Auth::user()->clinic_id ?? 1,
                    ]
                );

                Cache::forget("admin_stats_total_payments_month");

                return view('admin.payments.success', compact('payment'));
            } else {
                $payment = Payment::where('reference', $request->reference)->first();
                return view('admin.payments.failed', [
                    'payment' => $payment,
                    'errorMessage' => 'Payment was not successful. Please try again.'
                ]);
            }
        } catch (\Exception $e) {
            return view('admin.payments.failed', [
                'errorMessage' => 'Payment verification failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show invoice for a payment
     */
    public function invoice(Payment $payment)
    {
        $payment->load(['user', 'appointment.doctor', 'consultation']);
        return view('admin.payments.invoice', compact('payment'));
    }

    // =========================================================
    // NEW ADMIN WALLET TOP-UP METHODS
    // =========================================================

    /**
     * Show the form to initiate a Hospital Fund top-up payment.
     */
    public function showTopUpForm()
    {
        $publicKey = config('services.paystack.public_key'); 
        return view('admin.wallet.top_up_form', compact('publicKey'));
    }

    /**
     * Step 1: Initiate payment via the backend using the Secret Key.
     */
    public function initializeTopUp(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:100', 
            'email' => 'required|email',
        ]);

        try {
            $secretKey = config('services.paystack.secret_key');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->paystackBaseUrl . '/transaction/initialize', [
                'email' => $request->email,
                'amount' => $request->amount * 100, 
                'callback_url' => route('admin.payment.verify'), 
                'metadata' => [
                    'admin_id' => Auth::id(),
                    'purpose' => 'Hospital Fund Top-Up',
                ],
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'Paystack Initialization Error: ' . $response->body()], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server Error during initialization: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Step 2: Verify the payment after the user completes the transaction via the browser callback.
     * This handles BOTH wallet top-ups AND appointment payments.
     */
    public function verifyPayment(Request $request)
    {
        $reference = $request->query('reference');
        Log::info("Paystack Callback/Verify received for reference: " . $reference);

        if (!$reference) {
            Log::error("Paystack callback missing reference.");
            return redirect()->route('admin.dashboard')->with('error', 'Payment reference not found.');
        }

        try {
            $secretKey = config('services.paystack.secret_key');

            Log::info("Verifying Paystack transaction with reference: " . $reference);
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey, 
            ])->get($this->paystackBaseUrl . '/transaction/verify/' . $reference);

            Log::debug("Paystack verification response: ", $response->json() ?? ['raw' => $response->body()]);

            if ($response->successful() && $response->json('data.status') === 'success') {
                // --- PAYMENT WAS SUCCESSFUL ---
                Log::info("Paystack verification successful for reference: " . $reference);
                $transactionData = $response->json('data');
                $metadata = $transactionData['metadata'] ?? []; 

                $paymentMethod = 'card_online'; 
                if (isset($transactionData['channel'])) {
                    $channelMapping = [ /* your mapping here */ ]; 
                    $paymentMethod = $channelMapping[$transactionData['channel']] ?? 'card_online';
                }

                 $userId = $metadata['patient_id'] ?? Auth::id(); 

                $payment = Payment::updateOrCreate(
                    ['reference' => $reference],
                    [
                        'user_id' => $userId, 
                        'amount' => $transactionData['amount'] / 100, 
                        'method' => $paymentMethod,
                        'status' => 'paid', 
                        'transaction_date' => Carbon::parse($transactionData['paid_at'] ?? now())->toDateTimeString(), 
                        'clinic_id' => $metadata['clinic_id'] ?? (Auth::user() ? Auth::user()->clinic_id : 1), 
                        'consultation_id' => $metadata['consultation_id'] ?? null,
                    ]
                );
                Log::info("Payment record updated/created (ID: {$payment->id}) for reference: " . $reference);


                // --- Check if this was an Appointment Payment ---
                if (isset($metadata['purpose']) && str_contains($metadata['purpose'], 'Appointment Payment') && isset($metadata['consultation_id'])) {
                    Log::info("Processing successful APPOINTMENT payment for Consultation ID: " . $metadata['consultation_id']);
                    $consultation = Consultation::find($metadata['consultation_id']);

                    if ($consultation) {
                        // FIX: Update Consultation status to scheduled (Final Confirmation)
                        $consultation->status = 'scheduled'; 
                        $consultation->save();
                        Log::info("Consultation ID {$consultation->id} status updated to '{$consultation->status}'.");

                        // --- !!! CRITICAL: CREATE THE APPOINTMENT RECORD !!! ---
                        $appointment = new Appointment();
                        $appointment->patient_id = $consultation->patient_id;
                        $appointment->doctor_id = $consultation->doctor_id;
                        $appointment->appointment_time = $consultation->start_time;
                        $appointment->type = ($consultation->delivery_channel == 'virtual') ? 'telehealth' : 'clinic'; 
                        $appointment->status = 'pending'; // Requires doctor confirmation
                        $appointment->reason = $consultation->reason ?? 'Consultation Booked'; 
                        $appointment->consultation_id = $consultation->id;
                        
                        if ($appointment->save()) {
                            Log::info("Appointment record (ID: {$appointment->id}) created successfully.");
                            // Update the Payment record with the appointment_id
                            $payment->appointment_id = $appointment->id;
                            $payment->save();

                            // --- Send Notifications ---
                            $doctor = User::find($consultation->doctor_id);
                            $patient = User::find($consultation->patient_id);

                            // Notify Doctor (New Request)
                            if ($doctor && $patient) {
                                $message = "New appointment request: {$patient->name} scheduled for " . Carbon::parse($appointment->appointment_time)->format('M d, Y g:i A') . ". Please review.";
                                \App\Models\Notification::create([
                                    'user_id' => $doctor->id,
                                    'type' => 'appointment',
                                    'message' => $message,
                                    'is_read' => false,
                                    'channel' => 'database',
                                ]);
                                Log::info("Notification sent to Doctor ID: " . $doctor->id);
                            }

                            // Notify Patient (Payment Success, Pending Confirmation)
                             if ($patient) {
                                $drName = $doctor ? "Dr. " . $doctor->name : "the doctor";
                                $message = "Payment successful! Your appointment request with {$drName} for " . Carbon::parse($appointment->appointment_time)->format('M d, Y g:i A') . " is pending confirmation.";
                                \App\Models\Notification::create([
                                    'user_id' => $patient->id,
                                    'type' => 'appointment',
                                    'message' => $message,
                                    'is_read' => false,
                                    'channel' => 'database',
                                ]);
                                Log::info("Notification sent to Patient ID: " . $patient->id);
                            }

                            // --- DOUBLE WHISTLEBLOWER (Cache Clearing) ---
                            Cache::forget("admin_stats_total_payments_month");
                            Cache::forget("admin_stats_pending_appointments");
                            Cache::forget("admin_stats_recent_appointments");
                            // --- END WHISTLEBLOWER ---

                             return view('admin.payments.success', compact('payment', 'appointment', 'consultation'));


                        } else {
                            Log::error("!!! Failed to save Appointment record for Consultation ID: {$consultation->id}");
                             return view('admin.payments.failed', ['errorMessage' => 'Payment successful, but failed to create appointment record.']);
                        }
                    } else {
                        Log::error("!!! Consultation not found (ID: {$metadata['consultation_id']}) for successful payment reference: " . $reference);
                         return view('admin.payments.success', compact('payment'));
                    }
                }
                 // --- END APPOINTMENT HANDLING ---

                else {
                     // --- Handle other successful payment types (like Wallet Top-Up) ---
                     Log::info("Processing successful OTHER payment (e.g., Wallet Top-Up) for reference: " . $reference);
                     
                     Cache::forget("admin_stats_total_payments_month");
                     
                     return view('admin.payments.success', compact('payment'));
                }

            } else {
                // --- PAYMENT FAILED or Verification Failed ---
                $errorMessage = $response->json('message') ?? 'Payment verification failed or status not successful.';
                Log::error("Paystack verification failed for reference: {$reference}. Reason: {$errorMessage}");

                $payment = Payment::where('reference', $reference)->first();
                if ($payment) {
                    $payment->status = 'failed'; 
                    $payment->save();
                    Log::info("Payment record (ID: {$payment->id}) status updated to 'failed'.");

                    Cache::forget("admin_stats_total_payments_month");

                    $consultation = Consultation::find($payment->consultation_id);
                    if ($consultation && $consultation->status !== 'completed' && $consultation->status !== 'cancelled') {
                        $consultation->status = 'cancelled';
                        $consultation->save();
                        Log::info("Consultation ID {$consultation->id} status updated to 'cancelled' due to failed payment.");
                         
                        $patient = User::find($consultation->patient_id);
                         if ($patient) {
                             $message = "Your payment for the appointment scheduled for " . Carbon::parse($consultation->start_time)->format('M d, Y g:i A') . " failed. The appointment has been cancelled.";
                            \App\Models\Notification::create([ /* ... notification details ... */ 
                                'user_id' => $patient->id,
                                'type' => 'payment_fail',
                                'message' => $message,
                                'is_read' => false,
                                'channel' => 'database',
                            ]); 
                             Log::info("Failure Notification sent to Patient ID: " . $patient->id);
                         }
                    }
                }

                return view('admin.payments.failed', compact('payment', 'errorMessage'));
            }

        } catch (\Exception $e) {
            Log::error("!!! Exception during Paystack verification for reference {$reference}: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return view('admin.payments.failed', ['errorMessage' => 'Verification server error: ' . $e->getMessage()]);
        }
    }

    /**
     * Show pending payment page
     */
    public function showPendingPayment($reference = null)
    {
        $payment = null;
        if ($reference) {
            $payment = Payment::where('reference', $reference)->first();
        }
        
        return view('admin.payments.pending', compact('payment'));
    }

    /**
     * Initialize payment for an appointment/service
     */
    public function initializeAppointmentPayment(Request $request)
    {
        if ($request->isMethod('get')) {
            $consultationId = $request->input('consultation_id');
            $paymentId = $request->input('payment_id');
            $serviceId = $request->input('service_id');
            $patientId = $request->input('patient_id');
            
            $consultation = \App\Models\Consultation::findOrFail($consultationId);
            $payment = \App\Models\Payment::findOrFail($paymentId);
            $patient = \App\Models\User::findOrFail($consultation->patient_id);
            $service = \App\Models\Service::findOrFail($serviceId ?? $consultation->service_type);
            
            $publicKey = config('services.paystack.public_key');
            
            return view('admin.appointment-payment', compact('consultation', 'payment', 'patient', 'publicKey', 'service'));
        }
        
        $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'payment_id' => 'required|exists:payments,id',
            'email' => 'required|email',
        ]);

        $consultation = \App\Models\Consultation::findOrFail($request->consultation_id);
        $payment = \App\Models\Payment::findOrFail($request->payment_id);
        $service = \App\Models\Service::where('service_name', $consultation->service_type)->first();
        
        $amount = $consultation->fee;

        try {
            $secretKey = config('services.paystack.secret_key');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->paystackBaseUrl . '/transaction/initialize', [
                'email' => $request->email,
                'amount' => $amount * 100, 
                'callback_url' => route('admin.payment.verify'), 
                'metadata' => [
                    'patient_id' => $consultation->patient_id,
                    'service_id' => $service->id ?? null,
                    'consultation_id' => $consultation->id,
                    'payment_id' => $payment->id,
                    'purpose' => 'Appointment Payment for ' . $consultation->service_type,
                ],
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json(['error' => 'Paystack Initialization Error: ' . $response->body()], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server Error during initialization: ' . $e->getMessage()], 500);
        }
    }
}