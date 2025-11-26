<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Payment; 
use App\Services\PaymentService;
use Carbon\Carbon;

class CheckInController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Show the check-in queue page.
     */
    public function index()
    {
        // FIX: We now include 'pending' in the list so Cash patients show up.
        $patientsWaiting = Appointment::with('patient', 'doctor', 'payment')
            ->where('type', 'in_person')
            // Show Approved (Ready) AND Pending (Waiting for Cash/Approval)
            ->whereIn('status', ['approved', 'confirmed', 'pending']) 
            ->whereDate('appointment_time', Carbon::today())
            ->orderBy('appointment_time', 'asc')
            ->get();
            
        return view('admin.checkin.index', compact('patientsWaiting'));
    }

    /**
     * Process the patient check-in (Move to Nurse Queue).
     */
    public function checkInPatient(Request $request, Appointment $appointment)
    {
        // Strict Check: Can't check in if not paid
        if ($appointment->payment && $appointment->payment->status !== 'paid') {
            return redirect()->back()->with('error', 'Cannot check in. Payment is pending.');
        }

        $appointment->status = 'checked_in';
        $appointment->save();
        
        return redirect()->route('admin.checkin.index')
                         ->with('success', 'Patient checked in. Sent to Nurse station.');
    }

    /**
     * Confirm Cash Payment
     */
    public function confirmPayment(Request $request, Payment $payment)
    {
        if ($payment->status === 'paid') {
            return redirect()->back()->with('info', 'Payment is already confirmed.');
        }

        try {
            $payment->update([
                'status' => 'paid',
                'method' => 'cash_in_clinic',
                'transaction_date' => now(),
            ]);

            if ($payment->consultation_id) {
                $this->paymentService->finalizeAppointment($payment);
            }

            return redirect()->back()->with('success', 'Cash payment confirmed. Patient receiving receipt email.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error confirming payment: ' . $e->getMessage());
        }
    }
}