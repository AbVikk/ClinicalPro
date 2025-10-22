<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Patient;

class CreateTestPatient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patient:create-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test patient record';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Check if there are patient users
        $patientUser = User::where('role', 'patient')->first();

        if ($patientUser) {
            // Check if patient record exists
            $existingPatient = Patient::where('user_id', $patientUser->id)->first();
            
            if (!$existingPatient) {
                // Create patient record
                $patient = new Patient();
                $patient->user_id = $patientUser->id;
                $patient->phone = $patientUser->phone ?? '1234567890';
                $patient->save();
                
                $this->info('Created patient record with ID: ' . $patient->id . ' for user: ' . $patientUser->name);
            } else {
                $this->info('Patient record already exists with ID: ' . $existingPatient->id);
            }
        } else {
            $this->error('No patient users found');
        }

        return 0;
    }
}