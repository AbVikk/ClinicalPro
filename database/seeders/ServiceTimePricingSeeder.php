<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\ServiceTimePricing;

class ServiceTimePricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all services
        $services = Service::all();
        
        foreach ($services as $service) {
            // Check if time pricing already exists for this service
            $existingPricings = ServiceTimePricing::where('service_id', $service->id)->count();
            
            // Only create if no pricings exist
            if ($existingPricings == 0) {
                // Create time-based pricing for each service based on your rules:
                // 30 minutes = base price (100%)
                // 40 minutes = base price + 20% (120%)
                // 60 minutes = base price + 30% (130%)
                
                ServiceTimePricing::create([
                    'service_id' => $service->id,
                    'duration_minutes' => 30,
                    'price' => $service->price_amount, // Base price
                    'is_active' => true,
                ]);
                
                ServiceTimePricing::create([
                    'service_id' => $service->id,
                    'duration_minutes' => 40,
                    'price' => $service->price_amount * 1.2, // 20% increase
                    'is_active' => true,
                ]);
                
                ServiceTimePricing::create([
                    'service_id' => $service->id,
                    'duration_minutes' => 60,
                    'price' => $service->price_amount * 1.3, // 30% increase
                    'is_active' => true,
                ]);
            }
        }
    }
}