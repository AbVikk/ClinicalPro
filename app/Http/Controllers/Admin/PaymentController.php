<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http; // Added for Paystack API calls

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

                return redirect()->route('admin.payments.index')
                    ->with('success', 'Payment completed successfully.');
            } else {
                // Payment failed
                return redirect()->route('admin.payments.index')
                    ->with('error', 'Payment failed. Please try again.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.payments.index')
                ->with('error', 'Payment verification failed: ' . $e->getMessage());
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
                
                // --- CRITICAL: Create Payment Record ---
                Payment::create([
                    'user_id' => $transactionData['metadata']['admin_id'] ?? Auth::id(), // Use ID from metadata or current user
                    'amount' => $transactionData['amount'] / 100, // Convert back to NGN
                    'method' => 'paystack',
                    'status' => 'paid',
                    'reference' => $reference,
                    'transaction_date' => now(),
                    'clinic_id' => Auth::user()->clinic_id ?? 1,
                    // Note: If you have a separate HospitalFund model, update it here.
                ]);

                return redirect()->route('admin.payments.index')->with('success', 'Hospital Fund successfully topped up! The transaction will now appear in the payments list.');
            } else {
                return redirect()->route('admin.dashboard')->with('error', 'Payment verification failed or status is not successful.');
            }

        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Verification server error: ' . $e->getMessage());
        }
    }
}