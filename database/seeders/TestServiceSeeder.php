<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class TestServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test service
        Service::create([
            'service_name' => 'General Consultation',
            'service_type' => 'Consultation',
            'price_amount' => 5000.00,
            'price_currency' => 'NGN',
            'description' => 'General medical consultation with a qualified doctor',
            'is_active' => true,
            'default_duration' => 30,
        ]);
    }
}