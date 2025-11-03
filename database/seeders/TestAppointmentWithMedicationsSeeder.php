<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Medication;
use App\Models\User;
use Carbon\Carbon;

class TestAppointmentWithMedicationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a doctor and patient for testing
        $doctor = User::where('role', 'doctor')->first();
        $patient = User::where('role', 'patient')->first();
        
        if ($doctor && $patient) {
            // Create a test appointment
            $appointment = Appointment::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'appointment_time' => Carbon::now()->addDays(1),
                'status' => 'confirmed',
                'reason' => 'Routine checkup',
                'notes' => 'Regular health checkup'
            ]);
            
            // Create some test medications for this appointment
            $medications = [
                [
                    'appointment_id' => $appointment->id,
                    'medication_name' => 'Paracetamol',
                    'type' => 'Analgesic',
                    'dosage' => '500mg',
                    'duration' => '7 days',
                    'instructions' => 'Take 1 tablet every 6 hours as needed for pain'
                ],
                [
                    'appointment_id' => $appointment->id,
                    'medication_name' => 'Amoxicillin',
                    'type' => 'Antibiotic',
                    'dosage' => '500mg',
                    'duration' => '10 days',
                    'instructions' => 'Take 1 capsule twice daily with food'
                ],
                [
                    'appointment_id' => $appointment->id,
                    'medication_name' => 'Lisinopril',
                    'type' => 'Antihypertensive',
                    'dosage' => '10mg',
                    'duration' => '30 days',
                    'instructions' => 'Take 1 tablet daily in the morning'
                ]
            ];
            
            foreach ($medications as $medication) {
                Medication::create($medication);
            }
        }
    }
}