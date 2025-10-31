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
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache; // <-- ADD THIS "WHISTLEBLOWER" IMPORT

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
        
        // --- FIX HERE ---
        // $method = $request->method; // This line causes the error
        $method = $request->input('method'); // <-- This is the correct fix
        // --- END FIX ---

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

        // --- THIS IS THE "WHISTLEBLOWER" ---
        // A payment was created, so erase the "Total Payments" from the whiteboard.
        if ($status === 'paid') {
            Cache::forget("admin_stats_total_payments_month");
        }
        // --- END OF WHISTLEBLOWER ---

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
        
        // --- FIX HERE ---
        // $method = $request->method; // This line causes the error
        $method = $request->input('method'); // <-- This is the correct fix
        // --- END FIX ---
        
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

        // --- THIS IS THE "WHISTLEBLOWER" ---
        // A payment was updated. The total could be wrong if the status changed.
        // It's safest to just erase the "Total Payments" from the whiteboard.
        Cache::forget("admin_stats_total_payments_month");
        // --- END OF WHISTLEBLOWER ---

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified payment from storage
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        // --- THIS IS THE "WHISTLEBLOWER" ---
        // A payment was deleted. Erase the "Total Payments" from the whiteboard.
        Cache::forget("admin_stats_total_payments_month");
        // --- END OF WHISTLEBLOWER ---

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

                // --- THIS IS THE "WHISTLEBLOWER" ---
                // This is an old callback, but let's add it just in case.
                Cache::forget("admin_stats_total_payments_month");
                // --- END OF WHISTLEBLOWER ---

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
            $secretKey = config('services.paystack.secret_key');

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
     * This handles BOTH wallet top-ups AND appointment payments.
     */
    public function verifyPayment(Request $request)
    {
        $reference = $request->query('reference');
        Log::info("Paystack Callback/Verify received for reference: " . $reference); // Log entry

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

            // Log the full Paystack response for debugging
            Log::debug("Paystack verification response: ", $response->json() ?? ['raw' => $response->body()]);

            if ($response->successful() && $response->json('data.status') === 'success') {
                // --- PAYMENT WAS SUCCESSFUL ---
                Log::info("Paystack verification successful for reference: " . $reference);
                $transactionData = $response->json('data');
                $metadata = $transactionData['metadata'] ?? []; // Get metadata

                // Determine payment method
                $paymentMethod = 'card_online'; // Default
                if (isset($transactionData['channel'])) {
                    $channelMapping = [ /* your mapping here */ ]; // Keep your existing mapping
                    $paymentMethod = $channelMapping[$transactionData['channel']] ?? 'card_online';
                }

                // Find or create the Payment record
                // Use metadata patient_id if available, fallback to Auth::id() only for top-ups maybe?
                 $userId = $metadata['patient_id'] ?? Auth::id(); // Prioritize patient_id from metadata

                $payment = Payment::updateOrCreate(
                    ['reference' => $reference],
                    [
                        'user_id' => $userId, // Use determined user ID
                        'amount' => $transactionData['amount'] / 100, // Convert Kobo to NGN
                        'method' => $paymentMethod,
                        'status' => 'paid', // Mark as paid
                        'transaction_date' => Carbon::parse($transactionData['paid_at'] ?? now())->toDateTimeString(), // Use Paystack's paid time
                        // clinic_id might need to come from metadata if not admin top-up
                        'clinic_id' => $metadata['clinic_id'] ?? (Auth::user() ? Auth::user()->clinic_id : 1), // Get clinic from metadata or default
                         // Store consultation_id if present in metadata
                        'consultation_id' => $metadata['consultation_id'] ?? null,
                    ]
                );
                Log::info("Payment record updated/created (ID: {$payment->id}) for reference: " . $reference);


                // --- Check if this was an Appointment Payment ---
                if (isset($metadata['purpose']) && str_contains($metadata['purpose'], 'Appointment Payment') && isset($metadata['consultation_id'])) {
                    Log::info("Processing successful APPOINTMENT payment for Consultation ID: " . $metadata['consultation_id']);
                    $consultation = Consultation::find($metadata['consultation_id']);

                    if ($consultation) {
                        // Update Consultation status
                        $consultation->status = 'scheduled'; // Or 'confirmed' if no doctor approval needed
                        $consultation->save();
                        Log::info("Consultation ID {$consultation->id} status updated to '{$consultation->status}'.");

                        // --- !!! CREATE THE APPOINTMENT RECORD !!! ---
                        $appointment = new Appointment();
                        $appointment->patient_id = $consultation->patient_id;
                        $appointment->doctor_id = $consultation->doctor_id;
                        $appointment->appointment_time = $consultation->start_time;
                        // Determine appointment type based on consultation delivery channel or service
                        $appointment->type = ($consultation->delivery_channel == 'virtual') ? 'telehealth' : 'clinic'; // Example logic
                        $appointment->status = 'pending'; // Requires doctor confirmation
                        $appointment->reason = $consultation->reason ?? 'Consultation Booked'; // Get reason
                         // Add duration if you added the column
                         // $appointment->duration = $consultation->duration_minutes;

                         // Link to consultation and payment
                        $appointment->consultation_id = $consultation->id;
                        // $appointment->payment_id = $payment->id; // If you have a payment_id column on appointments

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
                                \App\Models\Notification::create([ /* ... notification details ... */ 
                                    'user_id' => $doctor->id, // Ensure this is present
                                    'type' => 'appointment',
                                    'message' => $message,
                                    'is_read' => false,
                                    'channel' => 'database',
                                ]); // Your notification code here
                                Log::info("Notification sent to Doctor ID: " . $doctor->id);
                            }

                            // Notify Patient (Payment Success, Pending Confirmation)
                             if ($patient) {
                                $drName = $doctor ? "Dr. " . $doctor->name : "the doctor";
                                $message = "Payment successful! Your appointment request with {$drName} for " . Carbon::parse($appointment->appointment_time)->format('M d, Y g:i A') . " is pending confirmation.";
                                \App\Models\Notification::create([ /* ... notification details ... */
                                    'user_id' => $patient->id, // Ensure this is present
                                    'type' => 'appointment',
                                    'message' => $message,
                                    'is_read' => false,
                                    'channel' => 'database',
                                ]); // Your notification code here
                                Log::info("Notification sent to Patient ID: " . $patient->id);
                            }

                            // --- THIS IS THE "DOUBLE WHISTLEBLOWER" ---
                            // 1. A payment was made.
                            Cache::forget("admin_stats_total_payments_month");
                            // 2. An appointment was created.
                            Cache::forget("admin_stats_pending_appointments");
                            Cache::forget("admin_stats_recent_appointments");
                            // --- END OF WHISTLEBLOWER ---

                            // Redirect to a specific Appointment Success page
                            // return redirect()->route('admin.appointment.success', ['appointment_id' => $appointment->id]);
                             return view('admin.payments.success', compact('payment', 'appointment', 'consultation'));


                        } else {
                            Log::error("!!! Failed to save Appointment record for Consultation ID: {$consultation->id}");
                             // Maybe redirect to a specific error page?
                             return view('admin.payments.failed', ['errorMessage' => 'Payment successful, but failed to create appointment record.']);
                        }
                    } else {
                        Log::error("!!! Consultation not found (ID: {$metadata['consultation_id']}) for successful payment reference: " . $reference);
                         // Redirect to general success page, but log the error
                         return view('admin.payments.success', compact('payment'));
                    }
                }
                 // --- END APPOINTMENT HANDLING ---

                else {
                     // --- Handle other successful payment types (like Wallet Top-Up) ---
                     Log::info("Processing successful OTHER payment (e.g., Wallet Top-Up) for reference: " . $reference);
                     
                     // --- THIS IS THE "WHISTLEBLOWER" ---
                     // A wallet top-up is a payment. Erase the "Total Payments" from the whiteboard.
                     Cache::forget("admin_stats_total_payments_month");
                     // --- END OF WHISTLEBLOWER ---
                     
                     // Add your wallet top-up logic here if needed
                     // Redirect to general success page
                     return view('admin.payments.success', compact('payment'));
                }

            } else {
                // --- PAYMENT FAILED or Verification Failed ---
                $errorMessage = $response->json('message') ?? 'Payment verification failed or status not successful.';
                Log::error("Paystack verification failed for reference: {$reference}. Reason: {$errorMessage}");

                // Find the existing Payment record (if any)
                $payment = Payment::where('reference', $reference)->first();
                if ($payment) {
                    $payment->status = 'failed'; // Mark as failed
                    $payment->save();
                    Log::info("Payment record (ID: {$payment->id}) status updated to 'failed'.");

                    // --- THIS IS THE "WHISTLEBLOWER" ---
                    // A payment was updated to "failed". Safest to clear the cache.
                    Cache::forget("admin_stats_total_payments_month");
                    // --- END OF WHISTLEBLOWER ---

                    // Find and cancel the associated consultation (if it exists)
                    $consultation = Consultation::find($payment->consultation_id);
                    if ($consultation && $consultation->status !== 'completed' && $consultation->status !== 'cancelled') {
                        $consultation->status = 'cancelled';
                        $consultation->save();
                        Log::info("Consultation ID {$consultation->id} status updated to 'cancelled' due to failed payment.");
                         // Notify Patient (Payment Failed)
                         $patient = User::find($consultation->patient_id);
                         if ($patient) {
                             $message = "Your payment for the appointment scheduled for " . Carbon::parse($consultation->start_time)->format('M d, Y g:i A') . " failed. The appointment has been cancelled.";
                            \App\Models\Notification::create([ /* ... notification details ... */ ]); // Your notification code here
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
            $secretKey = config('services.paystack.secret_key');

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