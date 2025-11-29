<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentReminderEmail;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:remind';
    protected $description = 'Send SMS, WhatsApp, and Email reminders';

    public function handle(SmsService $smsService)
    {
        $this->info('🚀 Starting Appointment Reminder Robot...');
        
        $tomorrow = Carbon::tomorrow();
        
        $appointments = Appointment::with(['patient', 'doctor', 'consultation.clinic'])
            ->whereDate('appointment_time', $tomorrow)
            ->whereIn('status', ['approved', 'confirmed']) 
            ->get();

        $count = $appointments->count();
        $this->info("📅 Found {$count} appointments for tomorrow.");

        if ($count === 0) {
            $this->info('✅ No reminders needed today.');
            return;
        }

        foreach ($appointments as $appt) {
            if (!$appt->patient) continue;

            $time = Carbon::parse($appt->appointment_time)->format('h:i A');
            $docName = $appt->doctor->name ?? 'the Doctor';

            // --- 1. SEND NOTIFICATIONS (PHONE) ---
            if ($appt->patient->phone) {
                $message = "Reminder: You have an appointment tomorrow at {$time} with Dr. {$docName}. Please arrive early.";
                
                // A. Send SMS (Reliable fallback)
                $smsService->send($appt->patient->phone, $message, 'sms');
                $this->info("   -> SMS sent to {$appt->patient->name}");

                // B. Send WhatsApp (NEW)
                // Note: This requires your Termii WhatsApp Device to be active
                $smsService->send($appt->patient->phone, $message, 'whatsapp'); 
                $this->info("   -> WhatsApp sent to {$appt->patient->name}");
            }

            // --- 2. SEND EMAIL ---
            if ($appt->patient->email) {
                try {
                    Mail::to($appt->patient->email)->send(new AppointmentReminderEmail($appt));
                    $this->info("   -> Email sent to {$appt->patient->name}");
                } catch (\Exception $e) {
                    Log::error("[ReminderRobot] Email failed: " . $e->getMessage());
                }
            }
        }

        $this->info("🎉 Done! All notifications sent.");
    }
}