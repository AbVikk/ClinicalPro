<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        // Run our custom seeders
        $this->call([
            CentralWarehouseSeeder::class,
            DrugCategoriesTableSeeder::class,
            DrugMgTableSeeder::class,
            DrugsTableSeeder::class,
            MedicationTypeSeeder::class,
            MedicationDosageSeeder::class,
            MedicationSeeder::class,
            TestAppointmentWithMedicationsSeeder::class,
            TestPharmacistSeeder::class,
        ]);
    }
}