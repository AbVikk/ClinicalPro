<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test HOD user
        User::firstOrCreate([
            'email' => 'hod@test.com',
        ], [
            'name' => 'Test HOD',
            'password' => Hash::make('password'),
            'role' => 'hod',
            'department_id' => 1,
        ]);

        // Create test Matron user
        User::firstOrCreate([
            'email' => 'matron@test.com',
        ], [
            'name' => 'Test Matron',
            'password' => Hash::make('password'),
            'role' => 'matron',
            'department_id' => 1,
        ]);
    }
}