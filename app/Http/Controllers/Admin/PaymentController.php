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
use Illuminate\Support\Facades\DB;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    protected $paystackBaseUrl = 'https://api.paystack.co';
    protected $paymentService;

    /**
     * =========================================================================
     * REFRACTORED CONSTRUCTOR
     * =========================================================================
     * We inject the PaymentService here so the whole controller can use it.
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $payments = Payment::with(['user', 'appointment', 'consultation'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.payments.index', compact('payments'));
    }

    public function create()
    {
        $patients = User::where('role', 'patient')->get();
        $doctors = User::where('role', 'doctor')->get();
        $appointments = Appointment::with(['patient', 'doctor'])->get();
        
        return view('admin.payments.create', compact('patients', 'doctors', 'appointments'));
    }

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
        $payment = Payment::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'method' => $method,
            'status' => $status,
            'reference' => $request->reference,
            'transaction_date' => $request->transaction_date ?? now(),
            'clinic_id' => Auth::user()->clinic_id ?? 1, 
        ]);
        if ($status === 'paid') {
            Cache::forget("admin_stats_total_payments_month");
        }
        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment added successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['user', 'appointment', 'consultation']);
        return view('admin.payments.show', compact('payment'));
    }

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
        Cache::forget("admin_stats_total_payments_month");
        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        Cache::forget("admin_stats_total_payments_month");
        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment deleted successfully.');
    }

    public function invoiceList()
    {
        $payments = Payment::with(['user', 'appointment.doctor'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.invoice', compact('payments'));
    }

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

    public function invoice(Payment $payment)
    {
        $payment->load(['user', 'appointment.doctor', 'consultation']);
        return view('admin.payments.invoice', compact('payment'));
    }

    public function showTopUpForm()
    {
        $publicKey = config('services.paystack.public_key'); 
        return view('admin.wallet.top_up_form', compact('publicKey'));
    }

    public function initializeTopUp(Request $request)
    {
        $request->validate(['amount' => 'required|integer|min:100', 'email' => 'required|email']);
        
        // 1. Prepare data for the "expert"
        $email = $request->email;
        $amount = $request->amount;
        $metadata = [
            'admin_id' => Auth::id(), 
            'purpose' => 'Hospital Fund Top-Up',
            'success_view' => 'admin.payments.success', // Admin success view
            'failed_view' => 'admin.payments.failed', // Admin failed view
        ];

        // 2. Ask the "expert" to do the work
        $response = $this->paymentService->initializePaymentTransaction($email, $amount, $metadata);

        // 3. Handle the expert's response
        if (isset($response['error'])) {
            return response()->json($response, 500);
        }
        return response()->json($response);
    }

    /**
     * This function is now thin and refactored.
     */
    public function verifyPayment(Request $request)
    {
        $reference = $request->query('reference');
        
        Log::info("[PaymentController] Received Paystack verification callback for reference: " . $reference);

        if (!$reference) {
            return redirect()->route('admin.dashboard')->with('error', 'Payment reference not found.');
        }

        try {
            // 1. MANAGER: Tell the Expert Worker (PaymentService) to handle the verification.
            $result = $this->paymentService->handleVerification($reference);

            // 2. MANAGER: Handle the outcome based on the result.
            // Get the payment to check metadata
            $payment = Payment::where('reference', $reference)->first();
            
            if ($result instanceof Payment && $result->status === 'paid') {
                // Check if this payment was initiated by a nurse
                // Since metadata is already an array due to model casting, we don't need to decode it
                $paymentMetadata = $payment->metadata ?? [];
                $roleInitiator = $paymentMetadata['role_initiator'] ?? null;
                
                if ($roleInitiator === 'nurse') {
                    // For nurse payments, redirect to nurse payment success route
                    // Pass reference as query parameter instead of route parameter
                    \Illuminate\Support\Facades\Log::info('Redirecting nurse payment to success page', [
                        'reference' => $result->reference,
                        'route' => route('nurse.payments.success.public', ['reference' => $result->reference])
                    ]);
                    return redirect()->route('nurse.payments.success.public', ['reference' => $result->reference])
                                   ->with('success', 'Payment successful! Appointment has been confirmed.');
                } else {
                    // For admin and other payments, return the view as before
                    return view('admin.payments.success', ['payment' => $result]);
                }
            } else {
                 $errorMessage = 'Payment verification failed or was unsuccessful.';
                 
                 // Check if this payment was initiated by a nurse
                 if ($payment) {
                     // Since metadata is already an array due to model casting, we don't need to decode it
                     $paymentMetadata = $payment->metadata ?? [];
                     $roleInitiator = $paymentMetadata['role_initiator'] ?? null;
                     
                     if ($roleInitiator === 'nurse') {
                         return redirect()->route('nurse.payments.failed.public', ['reference' => $reference])
                                        ->with('error', $errorMessage);
                     }
                 }
                 
                 // Handle failed payments for admin and others
                 return view('admin.payments.failed', ['errorMessage' => $errorMessage]);
            }

        } catch (\Exception $e) {
            Log::error("[PaymentController] FATAL EXCEPTION in verification process: " . $e->getMessage());
            $errorMessage = 'Verification server error: ' . $e->getMessage();
            return view('admin.payments.failed', compact('errorMessage'));
        }
    }

    public function showPendingPayment($reference = null)
    {
        $payment = null;
        if ($reference) {
            $payment = Payment::where('reference', $reference)->first();
        }
        
        return view('admin.payments.pending', compact('payment'));
    }

    public function initializeAppointmentPayment(Request $request)
    {
        // This 'GET' part just shows the page, it's fine.
        if ($request->isMethod('get')) {
            $consultationId = $request->input('consultation_id');
            $paymentId = $request->input('payment_id');
            $serviceId = $request->input('service_id');
            $patientId = $request->input('patient_id');
            $consultation = Consultation::findOrFail($consultationId);
            $payment = Payment::findOrFail($paymentId);
            $patient = User::findOrFail($consultation->patient_id);
            $service = Service::findOrFail($serviceId ?? $consultation->service_type);
            $publicKey = config('services.paystack.public_key');
            return view('admin.appointment-payment', compact('consultation', 'payment', 'patient', 'publicKey', 'service'));
        }

        // --- This is the 'POST' part, which we are refactoring ---
        $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'payment_id' => 'required|exists:payments,id',
            'email' => 'required|email',
        ]);

        $consultation = Consultation::findOrFail($request->consultation_id);
        $payment = Payment::findOrFail($request->payment_id);
        $service = Service::where('service_name', $consultation->service_type)->first();
        
        // 1. Prepare data for the "expert"
        $email = $request->email;
        $amount = $consultation->fee; // Get the fee from the consultation
        $metadata = [
            'patient_id' => $consultation->patient_id,
            'service_id' => $service->id ?? null,
            'consultation_id' => $consultation->id,
            'payment_id' => $payment->id, // This is critical
            'purpose' => 'Appointment Payment for ' . $consultation->service_type,
            'success_view' => 'admin.payments.success', // Admin success view
            'failed_view' => 'admin.payments.failed', // Admin failed view
        ];

        // 2. Ask the "expert" to do the work
        $response = $this->paymentService->initializePaymentTransaction($email, $amount, $metadata);

        // 3. Handle the expert's response
        if (isset($response['error'])) {
            return response()->json($response, 500);
        }
        
        // The service gives us the full response, which is what the JS expects
        return response()->json($response);
    }
}