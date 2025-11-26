<?php

namespace App\Services;

use App\Models\User;
use App\Models\Service;
use App\Models\Consultation;
use App\Models\Payment;
use App\Models\Appointment;
use App\Models\Notification;
use App\Events\DoctorAlert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmationEmail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AppointmentBookingService
{
    /**
     * Handles the initial booking request.
     */
    public function createAppointment(array $validatedData, string $paymentMethod): array
    {
        Log::info('[AppointmentBookingService] Starting appointment creation...');

        return DB::transaction(function () use ($validatedData, $paymentMethod) {
            
            // 1. Double-Submission Check
            $existing = Consultation::where('patient_id', $validatedData['patient_id'])
                ->where('doctor_id', $validatedData['doctor_id'])
                ->where('start_time', $validatedData['appointment_date'])
                ->whereIn('status', ['pending', 'scheduled'])
                ->first();
                
            if ($existing) {
                $existingPayment = Payment::where('consultation_id', $existing->id)->latest()->first();
                return [
                    'consultation' => $existing,
                    'payment' => $existingPayment,
                    'service' => Service::find($validatedData['service_id'])
                ];
            }

            try {
                $patient = User::where('user_id', $validatedData['patient_id'])->firstOrFail();
                $service = Service::findOrFail($validatedData['service_id']);
                $doctor = User::findOrFail($validatedData['doctor_id']);

                $calculatedFee = $this->calculateFee($service, (int)$validatedData['service_duration']);

                $isVirtual = isset($validatedData['clinic_id']) && $validatedData['clinic_id'] === 'virtual';
                $locationId = $isVirtual ? 1 : (int)$validatedData['clinic_id'];

                // 2. Status Logic
                $consultationStatus = ($paymentMethod === Payment::METHOD_CASH) ? 'scheduled' : 'pending';
                $paymentStatus = ($paymentMethod === Payment::METHOD_CASH) 
                    ? Payment::STATUS_PENDING_VERIFICATION 
                    : Payment::STATUS_PENDING;

                // 3. Create Consultation
                $consultation = Consultation::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'location_id' => $locationId,
                    'delivery_channel' => $isVirtual ? 'virtual' : 'in_clinic',
                    'service_type' => $service->service_name,
                    'fee' => $calculatedFee,
                    'status' => $consultationStatus,
                    'start_time' => $validatedData['appointment_date'],
                    'duration_minutes' => $validatedData['service_duration'],
                    'reason' => $validatedData['reason'] ?? null,
                ]);

                // 4. Create Payment
                $payment = Payment::create([
                    'user_id' => $patient->id,
                    'consultation_id' => $consultation->id,
                    'clinic_id' => $locationId,
                    'amount' => $calculatedFee,
                    'method' => $paymentMethod,
                    'status' => $paymentStatus,
                    'transaction_date' => now(),
                    'reference' => 'CONS-' . $consultation->id . '-' . strtoupper(Str::random(6)),
                ]);

                // 5. If Cash, finalize immediately. If Online, wait for webhook.
                if ($paymentMethod === Payment::METHOD_CASH) {
                    $this->createAppointmentRecord($consultation, $payment);
                }
                
                $this->clearRelevantCaches();

                return [
                    'consultation' => $consultation,
                    'payment' => $payment,
                    'service' => $service
                ];

            } catch (\Exception $e) {
                Log::error("[AppointmentBookingService] Error: " . $e->getMessage());
                throw $e; 
            }
        });
    }

    private function calculateFee(Service $service, int $duration): float
    {
        $basePrice = $service->price_amount;
        $baseDuration = $service->default_duration ?? 30;
        if ($baseDuration == 0) $baseDuration = 30; 
        $ratePerMinute = round($basePrice / $baseDuration, 2); 
        $calculatedFee = max($duration * $ratePerMinute, 15 * $ratePerMinute); 
        return round($calculatedFee, 2);
    }

    /**
     * Creates the official Appointment record.
     * Public because PaymentService uses this on success.
     */
    public function createAppointmentRecord(Consultation $consultation, Payment $payment): Appointment
    {
        // Prevent duplicates
        $appointment = Appointment::firstOrCreate(
            ['consultation_id' => $consultation->id],
            [
                'patient_id' => $consultation->patient_id,
                'doctor_id' => $consultation->doctor_id,
                'appointment_time' => $consultation->start_time,
                'type' => ($consultation->delivery_channel == 'virtual') ? 'telehealth' : 'in_person',
                'status' => 'pending', // Pending doctor acceptance
                'reason' => $consultation->reason ?? 'Consultation Booked',
                'payment_id' => $payment->id,
            ]
        );

        // Ensure payment is linked
        if ($payment->appointment_id !== $appointment->id) {
            $payment->appointment_id = $appointment->id;
            $payment->save();
        }

        // Notify Doctor & Patient via Database
        $this->sendAppointmentNotifications($appointment, $consultation->patient, User::find($consultation->doctor_id));

        // --- NEW: SEND EMAIL CONFIRMATION TO PATIENT ---
        try {
            // We reload the relationship to ensure doctor/clinic info is available for the email view
            $appointment->load(['patient', 'doctor', 'consultation.clinic']);
            
            if ($appointment->patient && $appointment->patient->email) {
                Mail::to($appointment->patient->email)->send(new AppointmentConfirmationEmail($appointment));
                Log::info("[AppointmentBookingService] Confirmation email sent to: " . $appointment->patient->email);
            }
        } catch (\Exception $e) {
            // We catch the error so the booking doesn't fail just because email failed
            Log::error("[AppointmentBookingService] Failed to send confirmation email: " . $e->getMessage());
        }
        // -----------------------------------------------

        return $appointment;
    }

    private function sendAppointmentNotifications(Appointment $appointment, User $patient, ?User $doctor)
    {
        if ($appointment->wasRecentlyCreated) {
            $time = Carbon::parse($appointment->appointment_time)->format('M d, Y g:i A');
            if ($doctor) {
                $message = "New appointment request: {$patient->name} scheduled for {$time}.";
                Notification::create(['user_id' => $doctor->id, 'type' => 'appointment', 'message' => $message, 'is_read' => false, 'channel' => 'database']);
                try { event(new DoctorAlert($doctor->id, $message)); } catch (\Exception $e) {}
            }
            Notification::create(['user_id' => $patient->id, 'type' => 'appointment', 'message' => "Appointment pending with Dr. " . ($doctor->name ?? ''), 'is_read' => false, 'channel' => 'database']);
        }
    }

    private function clearRelevantCaches()
    {
        Cache::forget('admin_stats_pending_appointments');
        Cache::forget('admin_stats_recent_appointments');
    }
}