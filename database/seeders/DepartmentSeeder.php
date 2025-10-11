<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Cardiology'],
            ['name' => 'Neurology'],
            ['name' => 'Orthopedics'],
            ['name' => 'Pediatrics'],
            ['name' => 'Emergency'],
            ['name' => 'Radiology'],
            ['name' => 'Laboratory'],
            ['name' => 'Pharmacy'],
        ];

        foreach ($departments as $department) {
            Department::firstOrCreate($department);
        }
    }
}