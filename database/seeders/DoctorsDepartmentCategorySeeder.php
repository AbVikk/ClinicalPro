<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorsDepartmentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing doctors with department and category information
        DB::table('doctors_new')->where('id', 1)->update([
            'department_id' => 1, // Internal Medicine
            'category_id' => 1,   // Cardiology
            'specialization' => 'Cardiology',
            'updated_at' => now(),
        ]);
        
        // Add more sample doctors
        DB::table('doctors_new')->insert([
            [
                'user_id' => 2,
                'doctor_id' => 'DOC002',
                'specialization' => 'Neurology',
                'department_id' => 1, // Internal Medicine
                'category_id' => 2,   // Neurology
                'license_number' => 'LIC002',
                'proof_of_identity' => null,
                'status' => 'active',
                'availability' => json_encode([
                    'Monday' => ['available' => true, 'slots' => ['09:00-12:00', '14:00-17:00']],
                    'Tuesday' => ['available' => true, 'slots' => ['10:00-13:00']],
                    'Wednesday' => ['available' => true],
                    'Thursday' => ['available' => true],
                    'Friday' => ['available' => true]
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'doctor_id' => 'DOC003',
                'specialization' => 'Orthopedic Surgery',
                'department_id' => 2, // Surgery
                'category_id' => 3,   // Orthopedics
                'license_number' => 'LIC003',
                'proof_of_identity' => null,
                'status' => 'active',
                'availability' => json_encode([
                    'Monday' => ['available' => true, 'slots' => ['09:00-12:00', '14:00-17:00']],
                    'Tuesday' => ['available' => true, 'slots' => ['10:00-13:00']],
                    'Wednesday' => ['available' => true],
                    'Thursday' => ['available' => true],
                    'Friday' => ['available' => true]
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}