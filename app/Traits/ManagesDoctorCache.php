<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

trait ManagesDoctorCache
{
    /**
     * Get cache keys unique to the current doctor.
     * These are the keys used in the dashboard that need refreshing when data changes.
     */
    protected function getDoctorCacheKeys($doctorId)
    {
        return [
            "doctor_{$doctorId}_todays_appointments",
            "doctor_{$doctorId}_upcoming_appointments",
            "doctor_{$doctorId}_pending_tasks",
            "doctor_{$doctorId}_recent_prescriptions",
            "doctor_{$doctorId}_patient_visits_chart",
            "doctor_{$doctorId}_last_month_visits",
            "doctor_{$doctorId}_last_year_total",
            "doctor_{$doctorId}_total_appointments",
            "doctor_{$doctorId}_online_consultations",
            "doctor_{$doctorId}_cancelled_appointments",
            "doctor_{$doctorId}_total_patients",
            "doctor_{$doctorId}_follow_ups",
            "doctor_{$doctorId}_schedule",
            "doctor_{$doctorId}_top_patients",
            "doctor_{$doctorId}_total_request_and_notification_count",
            "doctor_{$doctorId}_notification_count"
        ];
    }

    /**
     * Helper to flush all cache keys for a doctor.
     * Call this whenever you update data (approve appt, save notes, etc).
     * * @param int|null $userId Optional user ID. Defaults to currently authenticated user.
     */
    protected function flushDoctorCache($userId = null)
    {
        $doctorId = $userId ?? Auth::id();
        
        if (!$doctorId) return;

        $keys = $this->getDoctorCacheKeys($doctorId);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}