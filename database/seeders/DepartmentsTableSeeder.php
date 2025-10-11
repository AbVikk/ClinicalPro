<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            [
                'name' => 'Internal Medicine',
                'description' => 'Department specializing in the diagnosis and treatment of adult diseases',
                'head_id' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Surgery',
                'description' => 'Department providing surgical treatment for various conditions',
                'head_id' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pediatrics',
                'description' => 'Department specializing in the care of infants, children, and adolescents',
                'head_id' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Emergency',
                'description' => 'Department providing immediate care for acute illnesses and injuries',
                'head_id' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Radiology',
                'description' => 'Department specializing in diagnostic imaging and radiological procedures',
                'head_id' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}