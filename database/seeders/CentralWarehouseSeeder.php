<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clinic;

class CentralWarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the central warehouse
        Clinic::updateOrCreate(
            ['name' => 'Central Warehouse'],
            [
                'address' => 'Main Storage Facility',
                'is_physical' => true,
                'is_warehouse' => true,
            ]
        );
    }
}