<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update admin user
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // Create or update pharmacist user
        User::updateOrCreate(
            ['email' => 'pharmacist@test.com'],
            [
                'name' => 'Test Pharmacist',
                'password' => Hash::make('password123'),
                'role' => 'primary_pharmacist',
            ]
        );
    }
}