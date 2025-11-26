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
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Cache; 
use App\Services\PaymentService;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * THE TRAFFIC COP: Verify Payment & Redirect based on Role
     */
    public function verifyPayment(Request $request)
    {
        $reference = $request->query('reference');
        
        Log::info("[PaymentController] Verifying reference: " . $reference);

        if (!$reference) {
            if (Auth::check() && Auth::user()->role === 'nurse') {
                return redirect()->route('nurse.dashboard')->with('error', 'Payment reference missing.');
            }
            return redirect()->route('admin.index')->with('error', 'Payment reference not found.');
        }

        try {
            // 1. Delegate to Service
            $payment = $this->paymentService->handleVerification($reference);

            if (!$payment) {
                return $this->handleFailedRedirect($reference, 'Verification failed at gateway.');
            }

            // 2. Check Success Status
            if ($payment->status === Payment::STATUS_PAID || $payment->status === Payment::STATUS_COMPLETED) {
                
                // 3. TRAFFIC CONTROL: Check who started this payment
                $metadata = $payment->metadata ?? [];
                $roleInitiator = $metadata['role_initiator'] ?? null;

                // --- NURSE REDIRECTION ---
                if ($roleInitiator === 'nurse') {
                    return redirect()->route('nurse.payments.success.public', ['reference' => $payment->reference])
                                   ->with('success', 'Payment verified successfully!');
                }
                
                // --- PATIENT REDIRECTION ---
                if ($roleInitiator === 'patient') {
                    return redirect()->route('patient.dashboard')
                                   ->with('success', 'Payment successful!');
                }

                // --- DEFAULT: ADMIN REDIRECTION ---
                return view('admin.payments.success', ['payment' => $payment]);
            } 
            
            return $this->handleFailedRedirect($reference, 'Payment was not successful.');

        } catch (\Exception $e) {
            Log::error("[PaymentController] Exception: " . $e->getMessage());
            return $this->handleFailedRedirect($reference, 'Server error during verification.');
        }
    }

    /**
     * Helper to handle failed redirects based on who is logged in
     */
    private function handleFailedRedirect($reference, $message)
    {
        if (Auth::check() && Auth::user()->role === 'nurse') {
            return redirect()->route('nurse.payments.failed.public', ['reference' => $reference])
                           ->with('error', $message);
        }

        return view('admin.payments.failed', ['errorMessage' => $message]);
    }

    // --- OTHER METHODS (Standard CRUD) ---

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
            'method' => 'required|string',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Payment::create($request->all());
        Cache::forget("admin_stats_total_payments_month");
        
        return redirect()->route('admin.payments.index')->with('success', 'Payment added.');
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
        return view('admin.payments.edit', compact('payment', 'patients', 'doctors'));
    }

    public function update(Request $request, Payment $payment)
    {
        $payment->update($request->all());
        Cache::forget("admin_stats_total_payments_month");
        return redirect()->route('admin.payments.index')->with('success', 'Payment updated.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        Cache::forget("admin_stats_total_payments_month");
        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted.');
    }

    public function invoiceList()
    {
        $payments = Payment::with(['user', 'appointment.doctor'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.invoice', compact('payments'));
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
        
        $metadata = [
            'admin_id' => Auth::id(), 
            'purpose' => 'Hospital Fund Top-Up',
            'role_initiator' => 'admin'
        ];

        $response = $this->paymentService->initializePaymentTransaction($request->email, $request->amount, $metadata);

        if (isset($response['status']) && $response['status'] === false) {
            return response()->json($response, 500);
        }
        return response()->json($response);
    }
    
    public function initializePaystack(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'amount' => 'required|numeric|min:1', 
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
        }

        $metadata = $request->metadata ?? [];
        $metadata['role_initiator'] = 'admin'; 

        $result = $this->paymentService->initializePaymentTransaction($request->email, $request->amount, $metadata);
        
        return response()->json($result);
    }
    
    public function handlePaystackCallback(Request $request)
    {
        return $this->verifyPayment($request);
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
        if ($request->isMethod('get')) {
            $consultation = Consultation::find($request->consultation_id);
            $payment = Payment::find($request->payment_id);
            $patient = User::find($request->patient_id);
            $service = Service::find($request->service_id);
            $publicKey = config('services.paystack.public_key');
            
            return view('admin.appointment-payment', compact('consultation', 'payment', 'patient', 'publicKey', 'service'));
        }
        return response()->json(['error' => 'Method not allowed'], 405);
    }
}