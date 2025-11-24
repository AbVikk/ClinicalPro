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
            ],
            // Additional hospital services
            [
                'service_name' => 'Emergency Care',
                'service_type' => 'Emergency',
                'price_amount' => 20000.00,
                'price_currency' => 'NGN',
                'description' => 'Emergency medical care services',
                'is_active' => true
            ],
            [
                'service_name' => 'Intensive Care',
                'service_type' => 'Critical Care',
                'price_amount' => 150000.00,
                'price_currency' => 'NGN',
                'description' => 'Intensive care unit services',
                'is_active' => true
            ],
            [
                'service_name' => 'Surgery Consultation',
                'service_type' => 'Consultation',
                'price_amount' => 15000.00,
                'price_currency' => 'NGN',
                'description' => 'Pre-surgical consultation with specialist',
                'is_active' => true
            ],
            [
                'service_name' => 'Post-Operative Care',
                'service_type' => 'Treatment',
                'price_amount' => 25000.00,
                'price_currency' => 'NGN',
                'description' => 'Post-surgical care and monitoring',
                'is_active' => true
            ],
            [
                'service_name' => 'Maternity Care',
                'service_type' => 'Obstetrics',
                'price_amount' => 50000.00,
                'price_currency' => 'NGN',
                'description' => 'Prenatal and postnatal care services',
                'is_active' => true
            ],
            [
                'service_name' => 'Labor and Delivery',
                'service_type' => 'Obstetrics',
                'price_amount' => 150000.00,
                'price_currency' => 'NGN',
                'description' => 'Childbirth and delivery services',
                'is_active' => true
            ],
            [
                'service_name' => 'Neonatal Care',
                'service_type' => 'Pediatrics',
                'price_amount' => 75000.00,
                'price_currency' => 'NGN',
                'description' => 'Newborn care and treatment',
                'is_active' => true
            ],
            [
                'service_name' => 'Dialysis',
                'service_type' => 'Renal',
                'price_amount' => 45000.00,
                'price_currency' => 'NGN',
                'description' => 'Kidney dialysis treatment',
                'is_active' => true
            ],
            [
                'service_name' => 'Chemotherapy',
                'service_type' => 'Oncology',
                'price_amount' => 120000.00,
                'price_currency' => 'NGN',
                'description' => 'Cancer treatment therapy',
                'is_active' => true
            ],
            [
                'service_name' => 'Radiation Therapy',
                'service_type' => 'Oncology',
                'price_amount' => 100000.00,
                'price_currency' => 'NGN',
                'description' => 'Radiation treatment for cancer',
                'is_active' => true
            ],
            [
                'service_name' => 'Physical Examination',
                'service_type' => 'Preventive',
                'price_amount' => 8000.00,
                'price_currency' => 'NGN',
                'description' => 'Complete physical health examination',
                'is_active' => true
            ],
            [
                'service_name' => 'Health Screening',
                'service_type' => 'Preventive',
                'price_amount' => 15000.00,
                'price_currency' => 'NGN',
                'description' => 'Comprehensive health screening package',
                'is_active' => true
            ],
            [
                'service_name' => 'Vaccination Package',
                'service_type' => 'Preventive',
                'price_amount' => 25000.00,
                'price_currency' => 'NGN',
                'description' => 'Complete vaccination package',
                'is_active' => true
            ],
            [
                'service_name' => 'Nutrition Consultation',
                'service_type' => 'Consultation',
                'price_amount' => 10000.00,
                'price_currency' => 'NGN',
                'description' => 'Diet and nutrition expert consultation',
                'is_active' => true
            ],
            [
                'service_name' => 'Cardiology Consultation',
                'service_type' => 'Specialist',
                'price_amount' => 20000.00,
                'price_currency' => 'NGN',
                'description' => 'Heart specialist consultation',
                'is_active' => true
            ],
            [
                'service_name' => 'Orthopedic Consultation',
                'service_type' => 'Specialist',
                'price_amount' => 18000.00,
                'price_currency' => 'NGN',
                'description' => 'Bone and joint specialist consultation',
                'is_active' => true
            ],
            [
                'service_name' => 'Neurology Consultation',
                'service_type' => 'Specialist',
                'price_amount' => 22000.00,
                'price_currency' => 'NGN',
                'description' => 'Nervous system specialist consultation',
                'is_active' => true
            ],
            [
                'service_name' => 'Dermatology Consultation',
                'service_type' => 'Specialist',
                'price_amount' => 15000.00,
                'price_currency' => 'NGN',
                'description' => 'Skin specialist consultation',
                'is_active' => true
            ],
            [
                'service_name' => 'ENT Consultation',
                'service_type' => 'Specialist',
                'price_amount' => 16000.00,
                'price_currency' => 'NGN',
                'description' => 'Ear, Nose, and Throat specialist consultation',
                'is_active' => true
            ],
            [
                'service_name' => 'Gynecology Consultation',
                'service_type' => 'Specialist',
                'price_amount' => 17000.00,
                'price_currency' => 'NGN',
                'description' => 'Women\'s health specialist consultation',
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