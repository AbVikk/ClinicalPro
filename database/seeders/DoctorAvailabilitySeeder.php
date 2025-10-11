<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorAvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $availability = [
            'Monday' => ['available' => true, 'slots' => ['09:00-12:00', '14:00-17:00']],
            'Tuesday' => ['available' => true, 'slots' => ['10:00-13:00']],
            'Wednesday' => ['available' => true],
            'Thursday' => ['available' => true],
            'Friday' => ['available' => true]
        ];
        
        DB::table('doctors_new')
            ->where('id', 2)
            ->update(['availability' => json_encode($availability)]);
    }
}
