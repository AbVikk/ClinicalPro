<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PrescriptionTemplate;
use App\Models\User;
use App\Models\Drug;

class PrescriptionTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a doctor user to be the creator of the templates
        $doctor = User::where('role', 'doctor')->first();
        
        // Get sample drugs
        $lisinopril = Drug::where('name', 'Lisinopril')->first();
        $metformin = Drug::where('name', 'Metformin')->first();
        $hydrochlorothiazide = Drug::where('name', 'Hydrochlorothiazide')->first();
        
        if ($doctor) {
            // Create a hypertension template
            PrescriptionTemplate::create([
                'name' => 'Hypertension Treatment',
                'description' => 'Standard treatment protocol for hypertension patients',
                'created_by' => $doctor->id,
                'diagnosis' => 'Hypertension',
                'notes' => 'Monitor blood pressure regularly',
                'usage_count' => rand(20, 50), // Random usage count for demo
                'medications' => [
                    [
                        'drug_id' => $lisinopril ? $lisinopril->id : 1,
                        'dosage' => '10mg',
                        'route' => 'oral',
                        'frequency' => 'Once daily',
                        'duration' => '30 days',
                        'instructions' => 'Take in the morning',
                        'allow_refills' => true,
                        'refills' => 3,
                    ],
                    [
                        'drug_id' => $hydrochlorothiazide ? $hydrochlorothiazide->id : 4,
                        'dosage' => '12.5mg',
                        'route' => 'oral',
                        'frequency' => 'Once daily',
                        'duration' => '30 days',
                        'instructions' => 'Take in the morning with food',
                        'allow_refills' => true,
                        'refills' => 3,
                    ]
                ]
            ]);

            // Create a diabetes template
            PrescriptionTemplate::create([
                'name' => 'Type 2 Diabetes Management',
                'description' => 'Standard treatment protocol for Type 2 Diabetes patients',
                'created_by' => $doctor->id,
                'diagnosis' => 'Type 2 Diabetes',
                'notes' => 'Check blood sugar levels before meals',
                'usage_count' => rand(10, 30), // Random usage count for demo
                'medications' => [
                    [
                        'drug_id' => $metformin ? $metformin->id : 2,
                        'dosage' => '500mg',
                        'route' => 'oral',
                        'frequency' => 'Twice daily',
                        'duration' => '30 days',
                        'instructions' => 'Take with meals',
                        'allow_refills' => true,
                        'refills' => 2,
                    ]
                ]
            ]);
        }
    }
}