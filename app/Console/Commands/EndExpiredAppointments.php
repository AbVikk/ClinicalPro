<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class EndExpiredAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:end-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'End appointments that have expired (past their scheduled time)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for expired appointments...');
        
        // Find appointments that are in_progress and have expired
        $expiredInProgress = Appointment::where('status', 'in_progress')
            ->where('appointment_time', '<', now())
            ->get();
            
        // Find appointments that are confirmed but never started and have expired
        $expiredConfirmed = Appointment::where('status', 'confirmed')
            ->where('appointment_time', '<', now()->subHours(2)) // Expired 2 hours after scheduled time
            ->get();
            
        // Merge both collections
        $expiredAppointments = $expiredInProgress->merge($expiredConfirmed);
            
        $this->info('Found ' . $expiredAppointments->count() . ' expired appointments.');
        
        foreach ($expiredAppointments as $appointment) {
            try {
                // Update the appointment status to 'completed'
                $appointment->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                    'end_reason' => 'Session automatically ended due to time expiration'
                ]);
                
                // Update the consultation end_time if there's a linked consultation
                $payment = $appointment->payment;
                if ($payment && $payment->consultation_id) {
                    $consultation = Consultation::find($payment->consultation_id);
                    if ($consultation) {
                        $consultation->update([
                            'end_time' => now(),
                            'status' => 'completed'
                        ]);
                    }
                }
                
                $this->info('Ended appointment ID: ' . $appointment->id);
            } catch (\Exception $e) {
                Log::error('Error ending expired appointment ID ' . $appointment->id . ': ' . $e->getMessage());
                $this->error('Error ending appointment ID ' . $appointment->id . ': ' . $e->getMessage());
            }
        }
        
        $this->info('Finished checking for expired appointments.');
        
        return 0;
    }
}