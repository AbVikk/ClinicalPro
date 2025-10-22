<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;

class CreateTestAppointmentForDoctor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:create-test-for-doctor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test appointment for a doctor';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get the first doctor
        $doctor = Doctor::first();
        if (!$doctor) {
            $this->error('No doctor found in the database.');
            return 1;
        }
        
        $this->info('Found doctor: ' . $doctor->user->name);
        
        // Get the first patient
        $patient = Patient::first();
        if (!$patient) {
            $this->error('No patient found in the database.');
            return 1;
        }
        
        $this->info('Found patient: ' . $patient->user->name);
        
        // Create a test appointment
        $appointment = new Appointment();
        $appointment->doctor_id = $doctor->user_id;
        $appointment->patient_id = $patient->user_id;
        $appointment->appointment_time = now()->addDays(1);
        $appointment->type = 'telehealth';
        $appointment->status = 'confirmed';
        $appointment->notes = 'Test appointment created by command';
        $appointment->save();
        
        $this->info('Created appointment with ID: ' . $appointment->id);
        $this->info('Appointment time: ' . $appointment->appointment_time);
        $this->info('Doctor ID: ' . $appointment->doctor_id);
        $this->info('Patient ID: ' . $appointment->patient_id);
        
        return 0;
    }
}