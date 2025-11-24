<?php

namespace App\Services;

use App\Models\DoctorSchedule;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Consultation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * This service handles complex queries for finding
 * available doctors, locations, and appointment slots.
 */
class AppointmentQueryService
{
    /**
     * Find available LOCATIONS based on a selected date and time.
     * This logic is now centralized here.
     */
    public function getAvailableLocations(string $dateTimeString)
    {
        Log::info("=== AppointmentQueryService@getAvailableLocations Start ===");
        if (!$dateTimeString) {
            Log::warning("No date/time string received.");
            return response()->json(['locations' => []]);
        }

        try {
            $selectedDateTime = Carbon::createFromFormat('l d F Y - H:i', $dateTimeString);
        } catch (\Exception $e) {
            Log::error("!!! Date parsing error: " . $e->getMessage() . " | Input: " . $dateTimeString);
            return response()->json(['locations' => [], 'error' => 'Invalid date format.']);
        }

        $dayOfWeek = strtolower($selectedDateTime->format('l'));
        $time = $selectedDateTime->format('H:i:s');
        $date = $selectedDateTime->format('Y-m-d');
        Log::info("Checking Rulebook for: Day={$dayOfWeek}, Date={$date}, Time={$time}");

        $schedulesQuery = DoctorSchedule::with(['doctor', 'doctor.doctorProfile'])
            ->where('day_of_week', $dayOfWeek)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where(DB::raw('CAST(start_time AS TIME)'), '<=', $time)
            ->where(DB::raw('CAST(end_time AS TIME)'), '>', $time);

        $schedulesFound = $schedulesQuery->get();
        Log::info("Found " . $schedulesFound->count() . " schedules matching time/date criteria.");

        if ($schedulesFound->isEmpty()) {
            Log::info("=== AppointmentQueryService@getAvailableLocations End (No schedules) ===");
            return response()->json(['locations' => []]);
        }

        $verifiedDoctorIds = [];
        foreach ($schedulesFound as $schedule) {
            $doctorUser = $schedule->doctor;
            if (!$doctorUser) continue;
            if ($doctorUser->role !== 'doctor') continue;
            if ($doctorUser->status !== 'active') continue;

            $doctorProfile = $doctorUser->doctorProfile;
            if (!$doctorProfile) continue;
            if ($doctorProfile->status !== 'verified') continue;

            $verifiedDoctorIds[] = $doctorUser->id;
        }

        $uniqueVerifiedDoctorIds = array_unique($verifiedDoctorIds);
        Log::info("Unique Verified Doctor User IDs found: " . json_encode($uniqueVerifiedDoctorIds));

        $finalSchedules = $schedulesFound->whereIn('doctor_id', $uniqueVerifiedDoctorIds);
        Log::info("Found " . $finalSchedules->count() . " schedules linked to verified doctors.");

        $availableLocationIds = $finalSchedules->pluck('location')->unique()->values();
        Log::info("Unique location IDs from final schedules: " . json_encode($availableLocationIds));

        $locations = [];
        $clinicIds = [];
        foreach ($availableLocationIds as $locationId) {
            if ($locationId === 'virtual') {
                $locations[] = ['id' => 'virtual', 'name' => 'Virtual Session'];
            } else if (is_numeric($locationId)) {
                $clinicIds[] = (int)$locationId;
            }
        }

        if (!empty($clinicIds)) {
            $clinics = Clinic::whereIn('id', $clinicIds)->select('id', 'name')->get();
            foreach ($clinics as $clinic) {
                $locations[] = ['id' => $clinic->id, 'name' => $clinic->name];
            }
        }

        usort($locations, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        Log::info("Returning final locations list: " . json_encode($locations));
        Log::info("=== AppointmentQueryService@getAvailableLocations End ===");
        return response()->json(['locations' => $locations]);
    }

    /**
     * Get available doctors based on date, time, location, AND check for conflicts.
     * This logic is now centralized here.
     */
    public function getAvailableDoctors(string $dateTimeString, string $clinicId, int $duration)
    {
        Log::info("=== AppointmentQueryService@getAvailableDoctors Start ===");
        Log::info("Received: date='{$dateTimeString}', clinic='{$clinicId}', duration='{$duration}'");

        if (!$dateTimeString || !$clinicId) {
            Log::warning("Missing date/time string or clinic ID.");
            return response()->json(['doctors' => []]);
        }

        try {
            $appointmentStart = Carbon::createFromFormat('l d F Y - H:i', $dateTimeString);
            $appointmentEnd = $appointmentStart->copy()->addMinutes($duration);
            Log::info("Calculated Appointment Slot: Start={$appointmentStart->toDateTimeString()}, End={$appointmentEnd->toDateTimeString()}");
        } catch (\Exception $e) {
            Log::error("!!! Date parsing error: " . $e->getMessage() . " | Input: " . $dateTimeString);
            return response()->json(['doctors' => [], 'error' => 'Invalid date format.']);
        }

        $dayOfWeek = strtolower($appointmentStart->format('l'));
        $startTime = $appointmentStart->format('H:i:s');
        $date = $appointmentStart->format('Y-m-d');
        Log::info("Checking Schedule Rulebook for: Day={$dayOfWeek}, Date={$date}, Time={$startTime}, Location={$clinicId}");

        $scheduledDoctorIds = DoctorSchedule::where('location', $clinicId)
            ->where('day_of_week', $dayOfWeek)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where(DB::raw('CAST(start_time AS TIME)'), '<=', $startTime)
            ->where(DB::raw('CAST(end_time AS TIME)'), '>=', $appointmentEnd->format('H:i:s'))
            ->pluck('doctor_id')
            ->unique();
        Log::info("Found " . $scheduledDoctorIds->count() . " doctor IDs matching schedule rules.");

        if ($scheduledDoctorIds->isEmpty()) {
            Log::info("=== AppointmentQueryService@getAvailableDoctors End (No schedules) ===");
            return response()->json(['doctors' => []]);
        }

        $verifiedDoctorIds = User::whereIn('id', $scheduledDoctorIds)
            ->where('role', 'doctor')
            ->where('status', 'active')
            ->whereHas('doctorProfile', function ($query) {
                $query->where('status', 'verified');
            })
            ->pluck('id');
        Log::info("Found " . $verifiedDoctorIds->count() . " verified doctors matching schedule.");

        if ($verifiedDoctorIds->isEmpty()) {
            Log::info("=== AppointmentQueryService@getAvailableDoctors End (No verified) ===");
            return response()->json(['doctors' => []]);
        }

        Log::info("Checking for conflicts for doctor IDs: " . json_encode($verifiedDoctorIds->toArray()));
        $conflictingDoctorIds = Consultation::whereIn('doctor_id', $verifiedDoctorIds)
            ->whereNotIn('status', ['completed', 'missed', 'cancelled'])
            ->where(function ($query) use ($appointmentStart, $appointmentEnd) {
                $query->where('start_time', '<', $appointmentEnd)
                    ->where(DB::raw('DATE_ADD(start_time, INTERVAL duration_minutes MINUTE)'), '>', $appointmentStart);
            })
            ->pluck('doctor_id')
            ->unique();
        Log::info("Found " . $conflictingDoctorIds->count() . " doctors with conflicts.");

        $trulyAvailableDoctorIds = collect($verifiedDoctorIds)->diff($conflictingDoctorIds);
        Log::info("Final available doctor IDs: " . json_encode($trulyAvailableDoctorIds->toArray()));

        $doctors = User::whereIn('id', $trulyAvailableDoctorIds)
            ->select('id', 'name')
            ->get();
        Log::info("Returning " . $doctors->count() . " final available doctors.");
        Log::info("=== AppointmentQueryService@getAvailableDoctors End ===");
        return response()->json(['doctors' => $doctors]);
    }
}