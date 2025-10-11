<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DoctorSchedule;
use App\Models\Doctor;

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first doctor or create one if none exists
        $doctor = Doctor::first();
        
        if ($doctor) {
            // Create sample schedules for the doctor
            $schedules = [
                [
                    'doctor_id' => $doctor->id,
                    'day_of_week' => 'Monday',
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'is_available' => true,
                ],
                [
                    'doctor_id' => $doctor->id,
                    'day_of_week' => 'Tuesday',
                    'start_time' => '10:00:00',
                    'end_time' => '16:00:00',
                    'is_available' => true,
                ],
                [
                    'doctor_id' => $doctor->id,
                    'day_of_week' => 'Wednesday',
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'is_available' => true,
                ],
                [
                    'doctor_id' => $doctor->id,
                    'day_of_week' => 'Thursday',
                    'start_time' => '10:00:00',
                    'end_time' => '16:00:00',
                    'is_available' => true,
                ],
                [
                    'doctor_id' => $doctor->id,
                    'day_of_week' => 'Friday',
                    'start_time' => '09:00:00',
                    'end_time' => '13:00:00',
                    'is_available' => true,
                ],
                [
                    'doctor_id' => $doctor->id,
                    'day_of_week' => 'Saturday',
                    'start_time' => '10:00:00',
                    'end_time' => '12:00:00',
                    'is_available' => true,
                ],
                [
                    'doctor_id' => $doctor->id,
                    'day_of_week' => 'Sunday',
                    'start_time' => null,
                    'end_time' => null,
                    'is_available' => false,
                ],
            ];
            
            foreach ($schedules as $schedule) {
                DoctorSchedule::create($schedule);
            }
        }
    }
}