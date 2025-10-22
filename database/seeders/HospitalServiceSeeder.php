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