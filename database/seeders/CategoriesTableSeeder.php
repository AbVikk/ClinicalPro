<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Cardiology',
                'description' => 'Specializing in heart and cardiovascular system disorders',
                'department_id' => 1, // Internal Medicine
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Neurology',
                'description' => 'Deals with disorders of the nervous system',
                'department_id' => 1, // Internal Medicine
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Orthopedics',
                'description' => 'Focuses on the musculoskeletal system',
                'department_id' => 2, // Surgery
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pediatrics',
                'description' => 'Medical care for infants, children, and adolescents',
                'department_id' => 3, // Pediatrics
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dermatology',
                'description' => 'Specializing in skin conditions',
                'department_id' => 1, // Internal Medicine
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ophthalmology',
                'description' => 'Specializing in eye and vision care',
                'department_id' => 2, // Surgery
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}