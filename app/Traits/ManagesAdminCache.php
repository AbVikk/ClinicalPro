<?php

namespace App\Traits;
use Illuminate\Support\Facades\Cache;

trait ManagesAdminCache
{
    /**
     * Helper to get all admin stats cache keys.
     * This is our "whistleblower" list.
     */
    protected function getAdminCacheKeys()
    {
        return [
            "admin_stats_total_users",
            "admin_stats_new_registrations_7d",
            "admin_stats_prev_week_registrations",
            "admin_stats_pending_appointments",
            "admin_stats_prev_week_pending",
            "admin_stats_pending_invitations",
            "admin_stats_total_payments_month",
            "admin_stats_total_disbursements_month",
            "admin_stats_recent_appointments",
            "admin_stats_available_doctors",
            "admin_stats_new_patients_list",
        ];
    }
    
    /**
     * Helper to flush all admin stats cache keys.
     */
    protected function flushAdminStatsCache()
    {
        $keys = $this->getAdminCacheKeys();
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}