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
use Illuminate\Support\Facades\Http; // Added for Paystack API calls
use Illuminate\Support\Facades\Log; // Added for logging

class PaymentController extends Controller
{
    // Added Paystack API Base URL
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
        
        $method = $request->method;
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
            'clinic_id' => Auth::user()->clinic_id ?? 1, // Default to virtual clinic if not set
        ]);

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
        
        // Map database values back to form values for editing
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

        // Map form values to database values
        $methodMapping = [
            'credit_card' => 'card_online',
            'debit_card' => 'card_online',
            'cash' => 'cash_in_clinic',
            'cheque' => 'cash_in_clinic',
        ];
        
        $method = $request->method;
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

        $payment->update([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'method' => $method,
            'status' => $status,
            'reference' => $request->reference,
            'transaction_date' => $request->transaction_date ?? $payment->transaction_date,
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified payment from storage
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

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
            'amount' => 'required|numeric|min:100', // Paystack minimum is 100 kobo (1 NGN)
            'metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Convert amount to kobo (Paystack uses kobo for NGN transactions)
        $amountInKobo = $request->amount * 100;

        // Initialize Paystack transaction
        $paystack = new \Yabacon\Paystack(env('PAYSTACK_SECRET_KEY'));
        
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
        $paystack = new \Yabacon\Paystack(env('PAYSTACK_SECRET_KEY'));
        
        try {
            // Verify transaction
            $tranx = $paystack->transaction->verify([
                'reference' => $request->reference,
            ]);

            if ('success' === $tranx->data->status) {
                // Payment was successful
                // Create or update payment record
                $payment = Payment::updateOrCreate(
                    ['reference' => $request->reference],
                    [
                        'user_id' => Auth::id(),
                        'amount' => $tranx->data->amount / 100, // Convert back from kobo
                        'method' => 'card_online', // Paystack payments should use card_online
                        'status' => 'paid', // Paystack payments are paid
                        'transaction_date' => now(),
                        'clinic_id' => Auth::user()->clinic_id ?? 1,
                    ]
                );

                // Return custom success page
                return view('admin.payments.success', compact('payment'));
            } else {
                // Payment failed
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
        // Public key is safe to pass to the view for the JS modal
        $publicKey = config('services.paystack.public_key'); 
        return view('admin.wallet.top_up_form', compact('publicKey'));
    }

    /**
     * Step 1: Initiate payment via the backend using the Secret Key.
     * This prepares the transaction for the Admin's Hospital Fund Top-Up.
     */
    public function initializeTopUp(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:100', // Amount in NGN, minimum 100
            'email' => 'required|email',
        ]);

        try {
            // Retrieve Secret Key securely from environment configuration
            $secretKey = env('PAYSTACK_SECRET_KEY');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->paystackBaseUrl . '/transaction/initialize', [
                'email' => $request->email,
                'amount' => $request->amount * 100, // Paystack requires amount in Kobo/Cent
                'callback_url' => route('admin.payment.verify'), // Route for verification after payment
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
     * This uses the Secret Key for a final, secure verification check.
     */
    public function verifyPayment(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('admin.dashboard')->with('error', 'Payment reference not found.');
        }

        try {
            $secretKey = env('PAYSTACK_SECRET_KEY'); 
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
            ])->get($this->paystackBaseUrl . '/transaction/verify/' . $reference);

            if ($response->successful() && $response->json('data.status') === 'success') {
                $transactionData = $response->json('data');
                
                // Determine payment method from transaction data
                $paymentMethod = 'card_online'; // Default to card online
                if (isset($transactionData['channel'])) {
                    // Map Paystack channel to our payment methods
                    $channelMapping = [
                        'card' => 'card_online',
                        'bank' => 'bank_transfer',
                        'ussd' => 'bank_transfer',
                        'qr' => 'card_online',
                        'mobile_money' => 'card_online'
                    ];
                    $paymentMethod = $channelMapping[$transactionData['channel']] ?? 'card_online';
                }
                
                // --- CRITICAL: Create/Update Payment Record ---
                $payment = Payment::updateOrCreate(
                    ['reference' => $reference],
                    [
                        'user_id' => $transactionData['metadata']['patient_id'] ?? (Auth::id() ?? $transactionData['customer']['id']),
                        'amount' => $transactionData['amount'] / 100, // Convert back to NGN
                        'method' => $paymentMethod, // Use actual payment method from transaction
                        'status' => 'paid',
                        'transaction_date' => now(),
                        'clinic_id' => Auth::user()->clinic_id ?? 1,
                    ]
                );

                // Try to find the consultation associated with this payment
                $consultation = null;
                if (isset($transactionData['metadata']['consultation_id'])) {
                    // Use the consultation_id from metadata if available
                    $consultation = \App\Models\Consultation::find($transactionData['metadata']['consultation_id']);
                } else if ($payment && $payment->consultation_id) {
                    // Fallback to payment's consultation_id
                    $consultation = \App\Models\Consultation::find($payment->consultation_id);
                } else {
                    // Final fallback: Find consultation by patient_id and associated payment
                    $consultation = \App\Models\Consultation::where('patient_id', $transactionData['metadata']['patient_id'] ?? null)
                        ->whereHas('payments', function($query) use ($reference) {
                            $query->where('reference', $reference);
                        })->first();
                }
                
                // If we found a consultation, create the appointment
                if ($consultation) {
                    // Update consultation status to scheduled (if it wasn't already)
                    $consultation->status = 'scheduled'; // Keep as scheduled since payment is confirmed
                    $consultation->save();
                    
                    // ONLY CREATE APPOINTMENT AFTER SUCCESSFUL PAYMENT
                    // Create legacy appointment record for backward compatibility (confirmed after payment)
                    $appointment = new \App\Models\Appointment();
                    $appointment->patient_id = $consultation->patient_id;
                    $appointment->doctor_id = $consultation->doctor_id;
                    $appointment->appointment_time = $consultation->start_time;
                    $appointment->notes = $consultation->notes ?? '';
                    $appointment->type = 'telehealth'; // Assuming telehealth since we're creating a consultation
                    $appointment->status = 'pending'; // Appointment is pending doctor approval after payment
                    
                    // Save the appointment and check if it was successful
                    if ($appointment->save()) {
                        // Update the payment with the appointment ID for future reference
                        if (isset($payment)) {
                            $payment->appointment_id = $appointment->id;
                            $payment->save();
                        }
                        
                        // Create notification for the doctor
                        $doctor = \App\Models\User::find($consultation->doctor_id);
                        $patient = \App\Models\User::find($consultation->patient_id);
                        if ($doctor && $patient) {
                            $message = "New appointment request: {$patient->name} scheduled for " . $appointment->appointment_time->format('M d, Y g:i A');
                            $notification = \App\Models\Notification::create([
                                'user_id' => $doctor->id,
                                'type' => 'appointment',
                                'message' => $message,
                                'is_read' => false,
                                'channel' => 'database', // Default channel for in-app notifications
                            ]);
                        }
                        
                        // Return custom success page for appointment payment
                        return view('admin.payments.success', compact('payment'));
                    } else {
                        // Log error if appointment couldn't be saved
                        Log::error('Failed to save appointment after successful payment', [
                            'consultation_id' => $consultation->id,
                            'patient_id' => $consultation->patient_id,
                            'doctor_id' => $consultation->doctor_id,
                            'reference' => $reference
                        ]);
                        
                        // Return failed page with error message
                        return view('admin.payments.failed', [
                            'errorMessage' => 'Failed to create appointment record after successful payment. Please contact support.'
                        ]);
                    }
                } else {
                    // Log error if consultation couldn't be found
                    Log::error('Consultation not found for payment verification', [
                        'reference' => $reference,
                        'payment_id' => $payment->id ?? null,
                        'patient_id' => $transactionData['metadata']['patient_id'] ?? null,
                        'metadata' => $transactionData['metadata'] ?? null
                    ]);
                }

                // Return custom success page for general payment
                return view('admin.payments.success', compact('payment'));
            } else {
                // Update payment status to failed
                $payment = Payment::where('reference', $reference)->first();
                if ($payment) {
                    $payment->status = 'failed';
                    $payment->save();
                    
                    // Update consultation status to cancelled if it was for an appointment
                    $consultation = \App\Models\Consultation::whereHas('payments', function($query) use ($reference) {
                        $query->where('reference', $reference);
                    })->first();
                    
                    if ($consultation) {
                        $consultation->status = 'cancelled'; // Cancel consultation if payment fails
                        $consultation->save();
                        
                        // DO NOT create appointment record for failed payments
                    }
                }
                
                // Return custom failed page
                return view('admin.payments.failed', [
                    'payment' => $payment,
                    'errorMessage' => 'Payment verification failed or status is not successful.'
                ]);
            }

        } catch (\Exception $e) {
            // Log the exception
            Log::error('Payment verification error: ' . $e->getMessage(), [
                'reference' => $reference,
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return custom failed page with error message
            return view('admin.payments.failed', [
                'errorMessage' => 'Verification server error: ' . $e->getMessage()
            ]);
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
        // If this is a GET request, show the payment page
        if ($request->isMethod('get')) {
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
        
        // If this is a POST request, initialize the payment using existing payment record
        $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'payment_id' => 'required|exists:payments,id',
            'email' => 'required|email',
        ]);

        // Get the consultation and payment details
        $consultation = \App\Models\Consultation::findOrFail($request->consultation_id);
        $payment = \App\Models\Payment::findOrFail($request->payment_id);
        $service = \App\Models\Service::where('service_name', $consultation->service_type)->first();
        
        // Ensure the payment amount matches the consultation fee
        $amount = $consultation->fee;

        try {
            // Retrieve Secret Key securely from environment configuration
            $secretKey = env('PAYSTACK_SECRET_KEY');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->paystackBaseUrl . '/transaction/initialize', [
                'email' => $request->email,
                'amount' => $amount * 100, // Paystack requires amount in Kobo/Cent
                'callback_url' => route('admin.payment.verify'), // Route for verification after payment
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