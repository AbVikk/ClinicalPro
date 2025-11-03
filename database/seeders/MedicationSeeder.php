<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medication;
use App\Models\Appointment;
use App\Models\DrugCategory;
use App\Models\DrugMg;

class MedicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Common medications with their types and typical dosages
        $medications = [
            // Analgesics
            ['name' => 'Paracetamol', 'type' => 'Analgesic', 'typical_dosage' => '500mg'],
            ['name' => 'Ibuprofen', 'type' => 'Analgesic', 'typical_dosage' => '200mg'],
            ['name' => 'Aspirin', 'type' => 'Analgesic', 'typical_dosage' => '100mg'],
            ['name' => 'Morphine', 'type' => 'Analgesic', 'typical_dosage' => '10mg'],
            
            // Antibiotics
            ['name' => 'Amoxicillin', 'type' => 'Antibiotic', 'typical_dosage' => '500mg'],
            ['name' => 'Azithromycin', 'type' => 'Antibiotic', 'typical_dosage' => '250mg'],
            ['name' => 'Ciprofloxacin', 'type' => 'Antibiotic', 'typical_dosage' => '500mg'],
            
            // Antihypertensives
            ['name' => 'Lisinopril', 'type' => 'Antihypertensive', 'typical_dosage' => '10mg'],
            ['name' => 'Amlodipine', 'type' => 'Antihypertensive', 'typical_dosage' => '5mg'],
            ['name' => 'Losartan', 'type' => 'Antihypertensive', 'typical_dosage' => '50mg'],
            
            // Antidiabetics
            ['name' => 'Metformin', 'type' => 'Antidiabetic', 'typical_dosage' => '500mg'],
            ['name' => 'Insulin Glargine', 'type' => 'Antidiabetic', 'typical_dosage' => '100mg'],
            ['name' => 'Glimepiride', 'type' => 'Antidiabetic', 'typical_dosage' => '2mg'],
            
            // Cardiovascular
            ['name' => 'Atorvastatin', 'type' => 'Cardiovascular', 'typical_dosage' => '20mg'],
            ['name' => 'Clopidogrel', 'type' => 'Cardiovascular', 'typical_dosage' => '75mg'],
            
            // Respiratory
            ['name' => 'Salbutamol', 'type' => 'Respiratory', 'typical_dosage' => '100mg'],
            ['name' => 'Fluticasone', 'type' => 'Respiratory', 'typical_dosage' => '50mg'],
            
            // Others
            ['name' => 'Levothyroxine', 'type' => 'Endocrine', 'typical_dosage' => '50mg'],
            ['name' => 'Sertraline', 'type' => 'Antidepressant', 'typical_dosage' => '50mg'],
            ['name' => 'Loratadine', 'type' => 'Antihistamine', 'typical_dosage' => '10mg']
        ];

        // Get sample appointments for testing (if any exist)
        $appointments = Appointment::limit(5)->get();
        
        // If we have appointments, create sample medications for them
        if ($appointments->count() > 0) {
            foreach ($appointments as $appointment) {
                foreach ($medications as $med) {
                    Medication::create([
                        'appointment_id' => $appointment->id,
                        'medication_name' => $med['name'],
                        'type' => $med['type'],
                        'dosage' => $med['typical_dosage'],
                        'duration' => '7 days',
                        'instructions' => 'Take as directed'
                    ]);
                }
            }
        }
    }
}