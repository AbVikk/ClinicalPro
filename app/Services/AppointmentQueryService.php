<?php

namespace App\Services;

use App\Models\DoctorSchedule;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Consultation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentQueryService
{
    /**
     * Find available LOCATIONS based on a selected date and time.
     */
    public function getAvailableLocations(string $dateTimeString)
    {
        Log::info("--- LOOKING FOR LOCATIONS ---");
        Log::info("Input: " . $dateTimeString);

        if (!$dateTimeString) {
            return response()->json(['locations' => []]);
        }

        try {
            $selectedDateTime = Carbon::createFromFormat('l d F Y - H:i', $dateTimeString);
        } catch (\Exception $e) {
            Log::error("Date Parse Error: " . $e->getMessage());
            return response()->json(['locations' => [], 'error' => 'Invalid date.']);
        }

        $dayOfWeek = strtolower($selectedDateTime->format('l')); // e.g., 'monday'
        $time = $selectedDateTime->format('H:i:s');
        $date = $selectedDateTime->format('Y-m-d');
        
        Log::info("Search Criteria: Day={$dayOfWeek}, Date={$date}, Time={$time}");

        // 1. Find Schedules (Case-Insensitive Day Match)
        $schedulesQuery = DoctorSchedule::with(['doctor', 'doctor.doctorProfile'])
            // Fix: Check lowercase day against lowercase DB column to ensure match
            ->where(DB::raw('LOWER(day_of_week)'), $dayOfWeek)
            // Handle Date Ranges (NULL = Forever)
            ->where(function($q) use ($date) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $date);
            })
            ->where(function($q) use ($date) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $date);
            })
            // Handle Time
            ->where(DB::raw('CAST(start_time AS TIME)'), '<=', $time)
            ->where(DB::raw('CAST(end_time AS TIME)'), '>', $time);

        $schedulesFound = $schedulesQuery->get();
        Log::info("Schedules Found in DB: " . $schedulesFound->count());

        if ($schedulesFound->isEmpty()) {
            return response()->json(['locations' => []]);
        }

        $verifiedDoctorIds = [];
        foreach ($schedulesFound as $schedule) {
            $doc = $schedule->doctor;
            
            // Debugging why a doctor might be skipped
            if (!$doc) { Log::info("Schedule #{$schedule->id} skipped: No User linked."); continue; }
            
            // Flexible Role Check (case-insensitive)
            if (strtolower($doc->role) !== 'doctor') { 
                Log::info("Schedule #{$schedule->id} skipped: User role is '{$doc->role}'"); continue; 
            }
            
            // Flexible Status Check
            if (strtolower($doc->status) !== 'active') { 
                Log::info("Schedule #{$schedule->id} skipped: User status is '{$doc->status}'"); continue; 
            }

            // Flexible Profile Check (If profile exists, must be verified. If missing, we might allow or skip)
            if ($doc->doctorProfile) {
                if (strtolower($doc->doctorProfile->status) !== 'verified') {
                    Log::info("Schedule #{$schedule->id} skipped: Profile status '{$doc->doctorProfile->status}'"); 
                    continue;
                }
            } else {
                Log::warning("Schedule #{$schedule->id}: Doctor has no profile table entry. Skipping safety check.");
            }

            $verifiedDoctorIds[] = $doc->id;
        }

        $uniqueVerifiedDoctorIds = array_unique($verifiedDoctorIds);
        Log::info("Valid Doctor IDs: " . implode(',', $uniqueVerifiedDoctorIds));
        
        // Filter schedules to only valid doctors
        $finalSchedules = $schedulesFound->whereIn('doctor_id', $uniqueVerifiedDoctorIds);
        
        // Extract Locations
        $locationIds = $finalSchedules->pluck('location')->unique()->values();
        $locations = [];
        $dbClinicIds = [];

        foreach ($locationIds as $locId) {
            if ($locId === 'virtual') {
                $locations[] = ['id' => 'virtual', 'name' => 'Virtual Session'];
            } elseif (is_numeric($locId)) {
                $dbClinicIds[] = $locId;
            }
        }

        if (!empty($dbClinicIds)) {
            $clinics = Clinic::whereIn('id', $dbClinicIds)->get();
            foreach ($clinics as $clinic) {
                $locations[] = ['id' => $clinic->id, 'name' => $clinic->name];
            }
        }

        return response()->json(['locations' => $locations]);
    }

    /**
     * Get available doctors based on date, time, location.
     */
    public function getAvailableDoctors(string $dateTimeString, string $clinicId, int $duration)
    {
        if (!$dateTimeString || !$clinicId) return response()->json(['doctors' => []]);

        try {
            $start = Carbon::createFromFormat('l d F Y - H:i', $dateTimeString);
            $end = $start->copy()->addMinutes($duration);
        } catch (\Exception $e) {
            return response()->json(['doctors' => []]);
        }

        $dayOfWeek = strtolower($start->format('l')); 
        $startTime = $start->format('H:i:s');
        $date = $start->format('Y-m-d');

        // 1. Find Schedules
        $doctorIds = DoctorSchedule::where('location', $clinicId)
            ->where(DB::raw('LOWER(day_of_week)'), $dayOfWeek)
            ->where(function($q) use ($date) { $q->whereNull('start_date')->orWhere('start_date', '<=', $date); })
            ->where(function($q) use ($date) { $q->whereNull('end_date')->orWhere('end_date', '>=', $date); })
            ->where(DB::raw('CAST(start_time AS TIME)'), '<=', $startTime)
            ->where(DB::raw('CAST(end_time AS TIME)'), '>=', $end->format('H:i:s'))
            ->pluck('doctor_id')
            ->unique();

        if ($doctorIds->isEmpty()) return response()->json(['doctors' => []]);

        // 2. Validate Doctors
        $validDoctors = User::whereIn('id', $doctorIds)
            ->where('role', 'doctor')
            ->where('status', 'active')
            ->pluck('id');

        if ($validDoctors->isEmpty()) return response()->json(['doctors' => []]);

        // 3. Check Conflicts
        $conflicts = Consultation::whereIn('doctor_id', $validDoctors)
            ->whereNotIn('status', ['completed', 'missed', 'cancelled'])
            ->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                  ->where(DB::raw('DATE_ADD(start_time, INTERVAL duration_minutes MINUTE)'), '>', $start);
            })
            ->pluck('doctor_id')
            ->unique();

        $availableIds = $validDoctors->diff($conflicts);

        $doctors = User::whereIn('id', $availableIds)->select('id', 'name')->get();

        return response()->json(['doctors' => $doctors]);
    }
}