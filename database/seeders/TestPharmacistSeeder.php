<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class TestPharmacistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test pharmacist user
        User::updateOrCreate(
            ['email' => 'pharmacist@test.com'],
            [
                'name' => 'Test Pharmacist',
                'password' => bcrypt('password'),
                'role' => 'primary_pharmacist',
            ]
        );
    }
}