<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Consultation;
use App\Models\Appointment;
use App\Models\AppointmentDetail;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Events\DoctorAlert;

class PaymentService
{
    protected $paystackBaseUrl;
    protected $paystackSecretKey;
    protected $bookingService; 

    public function __construct()
    {
        $this->paystackBaseUrl = config('services.paystack.payment_url', 'https://api.paystack.co');
        $this->paystackSecretKey = config('services.paystack.secret_key');
        $this->bookingService = new AppointmentBookingService();
    }

    public function initializePaymentTransaction(string $email, float $amount, array $metadata)
    {
        // Set the global callback URL. The PaymentController will decide where to go next.
        $metadata['callback_url'] = route('admin.payment.verify'); 
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->paystackSecretKey,
                'Content-Type'  => 'application/json',
            ])->post("{$this->paystackBaseUrl}/transaction/initialize", [
                'email'    => $email,
                'amount'   => $amount * 100, 
                'metadata' => $metadata,
            ]);

            if ($response->successful()) {
                return $response->json();
            }
            Log::error("[PaymentService] Init Failed: " . $response->body());
            return ['status' => false, 'message' => 'Payment Gateway Error: ' . $response->status()];
        } catch (\Exception $e) {
            Log::error("[PaymentService] Connection Error: " . $e->getMessage());
            return ['status' => false, 'message' => 'Connection Error'];
        }
    }

    public function handleVerification(string $reference)
    {
        $existing = Payment::where('reference', $reference)->first();
        
        // Optimization: If already marked as paid, return immediately
        if ($existing && $existing->status === Payment::STATUS_PAID) {
            return $existing;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->paystackSecretKey,
            ])->get("{$this->paystackBaseUrl}/transaction/verify/{$reference}");

            if (!$response->successful() || $response->json('data.status') !== 'success') {
                Log::warning("[PaymentService] Payment verification failed for reference: " . $reference);
                return $this->handleFailedPayment($reference);
            }

            $data = $response->json('data');

            return DB::transaction(function () use ($data, $reference) {
                $meta = $data['metadata'] ?? [];
                
                $payment = Payment::updateOrCreate(
                    ['reference' => $reference],
                    [
                        'amount'           => $data['amount'] / 100,
                        'status'           => Payment::STATUS_PAID,
                        'method'           => Payment::METHOD_CARD_ONLINE,
                        'transaction_date' => Carbon::parse($data['paid_at']),
                        'user_id'          => $meta['patient_id'] ?? Auth::id(),
                        'consultation_id'  => $meta['consultation_id'] ?? null,
                        'clinic_id'        => $meta['clinic_id'] ?? 1,
                        'metadata'         => $meta, // CRITICAL: Save metadata so Controller can read role_initiator
                    ]
                );

                if (!empty($payment->consultation_id)) {
                    $this->finalizeAppointment($payment);
                }
                
                $this->clearRelevantCaches();
                return $payment;
            });

        } catch (\Exception $e) {
            Log::error("[PaymentService] Verification Exception: " . $e->getMessage());
            return null;
        }
    }

    public function finalizeAppointment(Payment $payment)
    {
        $consultation = Consultation::lockForUpdate()->find($payment->consultation_id);
        
        if (!$consultation) return;

        // 1. Update Consultation
        if ($consultation->status !== 'scheduled') {
            $consultation->update(['status' => 'scheduled']);
        }

        // 2. Create/Update Appointment via Booking Service
        $appt = $this->bookingService->createAppointmentRecord($consultation, $payment);
        
        // 3. Set Status to Pending (Waiting for Doctor)
        $appt->update(['status' => 'pending']);
        
        // 4. Create Details
        AppointmentDetail::firstOrCreate(['appointment_id' => $appt->id]);
        
        // 5. Notify
        $this->sendConfirmationNotification($appt);
    }
    
    protected function handleFailedPayment(string $reference)
    {
        $payment = Payment::where('reference', $reference)->first();
        if ($payment) {
            $payment->update(['status' => Payment::STATUS_FAILED]);
        }
        return $payment;
    }
    
    protected function sendConfirmationNotification(Appointment $appt) {
        try {
            $doctor = User::find($appt->doctor_id);
            if ($doctor) {
                $msg = "Action Required: New paid appointment with " . ($appt->patient->name ?? 'Patient');
                Notification::create([
                    'user_id' => $doctor->id, 
                    'type' => 'appointment', 
                    'message' => $msg, 
                    'is_read' => false, 
                    'channel' => 'database'
                ]);
                try { event(new DoctorAlert($doctor->id, $msg)); } catch (\Exception $e) {}
            }
        } catch (\Exception $e) {
            Log::warning("Notification Failed: " . $e->getMessage());
        }
    }
    
    protected function clearRelevantCaches()
    {
        Cache::forget('admin_stats_total_payments_month');
        Cache::forget('admin_stats_pending_appointments');
    }
}