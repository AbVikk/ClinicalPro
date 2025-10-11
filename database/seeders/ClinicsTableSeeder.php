<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClinicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed the first record for the virtual channel
        DB::table('clinics')->insert([
            'id' => 1,
            'name' => 'Virtual Consults Channel',
            'address' => 'N/A',
            'is_physical' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
