<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class HospitalServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'service_name' => 'General Consultation',
                'service_type' => 'Consultation',
                'price_amount' => 5000.00,
                'price_currency' => 'NGN',
                'description' => 'Standard doctor consultation',
                'is_active' => true
            ],
            [
                'service_name' => 'Pediatric Consultation',
                'service_type' => 'Consultation',
                'price_amount' => 7500.00,
                'price_currency' => 'NGN',
                'description' => 'Specialized consultation for children',
                'is_active' => true
            ],
            [
                'service_name' => 'Specialist Follow-up',
                'service_type' => 'Consultation',
                'price_amount' => 10000.00,
                'price_currency' => 'NGN',
                'description' => 'Follow-up visit with a specialist',
                'is_active' => true
            ],
            [
                'service_name' => 'IV Drip',
                'service_type' => 'Treatment',
                'price_amount' => 15000.00,
                'price_currency' => 'NGN',
                'description' => 'Intravenous fluid therapy',
                'is_active' => true
            ],
            [
                'service_name' => 'Minor Surgery',
                'service_type' => 'Procedure',
                'price_amount' => 25000.00,
                'price_currency' => 'NGN',
                'description' => 'Minor surgical procedures',
                'is_active' => true
            ],
            [
                'service_name' => 'Wound Dressing',
                'service_type' => 'Treatment',
                'price_amount' => 5000.00,
                'price_currency' => 'NGN',
                'description' => 'Professional wound care and dressing',
                'is_active' => true
            ],
            [
                'service_name' => 'Routine Blood Work',
                'service_type' => 'Diagnostic',
                'price_amount' => 12000.00,
                'price_currency' => 'NGN',
                'description' => 'Standard blood tests and analysis',
                'is_active' => true
            ],
            [
                'service_name' => 'X-Ray',
                'service_type' => 'Diagnostic',
                'price_amount' => 18000.00,
                'price_currency' => 'NGN',
                'description' => 'X-ray imaging services',
                'is_active' => true
            ],
            [
                'service_name' => 'ECG',
                'service_type' => 'Diagnostic',
                'price_amount' => 8000.00,
                'price_currency' => 'NGN',
                'description' => 'Electrocardiogram test',
                'is_active' => true
            ],
            [
                'service_name' => 'Dental Checkup',
                'service_type' => 'Dental',
                'price_amount' => 7000.00,
                'price_currency' => 'NGN',
                'description' => 'Routine dental examination',
                'is_active' => true
            ],
            [
                'service_name' => 'Teeth Cleaning',
                'service_type' => 'Dental',
                'price_amount' => 12000.00,
                'price_currency' => 'NGN',
                'description' => 'Professional teeth cleaning',
                'is_active' => true
            ],
            [
                'service_name' => 'Physiotherapy Session',
                'service_type' => 'Therapy',
                'price_amount' => 8000.00,
                'price_currency' => 'NGN',
                'description' => 'Physical therapy session',
                'is_active' => true
            ],
            [
                'service_name' => 'Ultrasound Scan',
                'service_type' => 'Diagnostic',
                'price_amount' => 15000.00,
                'price_currency' => 'NGN',
                'description' => 'Ultrasound imaging services',
                'is_active' => true
            ],
            [
                'service_name' => 'Vaccination',
                'service_type' => 'Preventive',
                'price_amount' => 5000.00,
                'price_currency' => 'NGN',
                'description' => 'Vaccination services',
                'is_active' => true
            ],
            [
                'service_name' => 'Eye Examination',
                'service_type' => 'Ophthalmology',
                'price_amount' => 6000.00,
                'price_currency' => 'NGN',
                'description' => 'Comprehensive eye examination',
                'is_active' => true
            ],
            [
                'service_name' => 'Prescription Refill',
                'service_type' => 'Pharmacy',
                'price_amount' => 2000.00,
                'price_currency' => 'NGN',
                'description' => 'Prescription refill services',
                'is_active' => true
            ],
            [
                'service_name' => 'Laboratory Test',
                'service_type' => 'Diagnostic',
                'price_amount' => 10000.00,
                'price_currency' => 'NGN',
                'description' => 'General laboratory testing',
                'is_active' => true
            ],
            [
                'service_name' => 'MRI Scan',
                'service_type' => 'Diagnostic',
                'price_amount' => 45000.00,
                'price_currency' => 'NGN',
                'description' => 'Magnetic Resonance Imaging scan',
                'is_active' => true
            ],
            [
                'service_name' => 'CT Scan',
                'service_type' => 'Diagnostic',
                'price_amount' => 35000.00,
                'price_currency' => 'NGN',
                'description' => 'Computed Tomography scan',
                'is_active' => true
            ],
            [
                'service_name' => 'Psychology Session',
                'service_type' => 'Mental Health',
                'price_amount' => 12000.00,
                'price_currency' => 'NGN',
                'description' => 'Psychology consultation session',
                'is_active' => true
            ]
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['service_name' => $service['service_name']],
                $service
            );
        }
    }
}