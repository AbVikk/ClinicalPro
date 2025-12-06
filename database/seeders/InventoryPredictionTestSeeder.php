<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Drug;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\User;
use Carbon\Carbon;

class InventoryPredictionTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some test drugs if they don't exist
        $drugs = [
            ['name' => 'Paracetamol', 'category' => 'Pain Relief', 'strength_mg' => 500, 'unit_price' => 0.10],
            ['name' => 'Ibuprofen', 'category' => 'Pain Relief', 'strength_mg' => 200, 'unit_price' => 0.15],
            ['name' => 'Amoxicillin', 'category' => 'Antibiotics', 'strength_mg' => 250, 'unit_price' => 0.25],
            ['name' => 'Salbutamol', 'category' => 'Respiratory', 'strength_mg' => 100, 'unit_price' => 0.30],
            ['name' => 'Omeprazole', 'category' => 'Gastrointestinal', 'strength_mg' => 20, 'unit_price' => 0.20],
        ];
        
        foreach ($drugs as $drugData) {
            Drug::firstOrCreate(
                ['name' => $drugData['name']],
                $drugData
            );
        }
        
        // Create a test patient
        $patient = User::firstOrCreate(
            ['email' => 'patient@test.com'],
            [
                'name' => 'Test Patient',
                'role' => 'patient',
                'password' => bcrypt('password'),
            ]
        );
        
        // Create prescriptions with items over the past year
        $startDate = Carbon::now()->subYear();
        for ($i = 0; $i < 50; $i++) {
            // Create prescription
            $prescription = Prescription::create([
                'patient_id' => $patient->id,
                'doctor_id' => 1, // Assuming doctor ID 1 exists
                'notes' => 'Test prescription',
                'created_at' => $startDate->copy()->addDays(rand(0, 365)),
            ]);
            
            // Add prescription items
            $drug = Drug::inRandomOrder()->first();
            PrescriptionItem::create([
                'prescription_id' => $prescription->id,
                'drug_id' => $drug->id,
                'medication_name' => $drug->name,
                'quantity' => rand(10, 100),
                'dosage' => $drug->strength_mg . 'mg',
                'duration' => rand(5, 30) . ' days',
                'use_pattern' => 'As directed',
                'instructions' => 'Take with food',
                'fulfillment_status' => 'dispensed', // Use a valid value
            ]);
        }
        
        $this->command->info('Test data seeded successfully!');
    }
}