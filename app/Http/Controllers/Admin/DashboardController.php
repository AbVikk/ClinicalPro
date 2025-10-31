<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Disbursement;
use App\Models\Invitation; // <-- ADDED THIS, IT'S IMPORTANT
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Set our cache time. 3600 seconds = 1 hour.
        $cacheTime = 3600;
        
        // --- CACHED ---
        // Get total users excluding admins
        $totalUsers = Cache::remember("admin_stats_total_users", $cacheTime, function () {
            return User::where('role', '!=', 'admin')->count();
        });
        
        // Calculate progress percentage: 1% for every 20 new users
        $progressPercentage = min(100, floor($totalUsers / 20));
        
        // Calculate the actual percentage for display
        $actualPercentage = min(100, ($totalUsers / 20) * 100);
        
        // --- CACHED ---
        // Get new registrations in the last 7 days (excluding admins)
        $newRegistrations = Cache::remember("admin_stats_new_registrations_7d", $cacheTime, function () {
            return User::where('role', '!=', 'admin')
                ->where('created_at', '>=', now()->subDays(7))
                ->count();
        });
            
        // Calculate progress for new registrations (1% per registration for visualization)
        $newRegProgress = min(100, $newRegistrations * 5); // 5% per registration, capped at 100%
        
        // --- CACHED ---
        // Calculate change percentage for new registrations compared to previous week
        $previousWeekRegistrations = Cache::remember("admin_stats_prev_week_registrations", $cacheTime, function () {
            return User::where('role', '!=', 'admin')
                ->whereBetween('created_at', [now()->subDays(14), now()->subDays(7)])
                ->count();
        });
            
        $regChangePercentage = 0;
        if ($previousWeekRegistrations > 0) {
            $regChangePercentage = round((($newRegistrations - $previousWeekRegistrations) / $previousWeekRegistrations) * 100);
        } elseif ($newRegistrations > 0) {
            $regChangePercentage = 100; // 100% increase if previous week was 0
        }
        
        // --- CACHED ---
        // Get pending appointments count
        $pendingAppointments = Cache::remember("admin_stats_pending_appointments", $cacheTime, function () {
            return Appointment::where('status', 'pending')->count();
        });
        
        // Calculate progress for pending appointments (10% per appointment for visualization)
        $pendingProgress = min(100, $pendingAppointments * 10); // 10% per appointment, capped at 100%
        
        // --- CACHED ---
        // Calculate change percentage for pending appointments compared to previous week
        $previousWeekPending = Cache::remember("admin_stats_prev_week_pending", $cacheTime, function () {
            return Appointment::where('status', 'pending')
                ->whereBetween('created_at', [now()->subDays(14), now()->subDays(7)])
                ->count();
        });
            
        $pendingChangePercentage = 0;
        if ($previousWeekPending > 0) {
            $pendingChangePercentage = round((($pendingAppointments - $previousWeekPending) / $previousWeekPending) * 100);
        } elseif ($pendingAppointments > 0) {
            $pendingChangePercentage = 100; // 100% increase if previous week was 0
        }
        
        // --- NEW CACHED QUERY ---
        // This query was being run from your index.blade.php file, which is very slow.
        // We moved it here and cached it.
        $pendingInvitations = Cache::remember("admin_stats_pending_invitations", $cacheTime, function () {
            return Invitation::where('used', false)->where('expires_at', '>', now())->count();
        });

        // Get system update/backup information from cache
        // This part was already using the cache, so it's already fast!
        $lastUpdate = Cache::get('system_last_update', 'Never');
        $lastBackup = Cache::get('system_last_backup', 'Never');
        
        // For demo purposes, let's set some default values if not set
        if ($lastUpdate === 'Never') {
            $lastUpdate = now()->subDays(2)->format('Y-m-d H:i:s');
            Cache::put('system_last_update', $lastUpdate, now()->addDays(30));
        }
        
        if ($lastBackup === 'Never') {
            $lastBackup = now()->subDay()->format('Y-m-d H:i:s');
            Cache::put('system_last_backup', $lastBackup, now()->addDays(30));
        }
        
        // Calculate days since last update and backup
        $daysSinceUpdate = ($lastUpdate !== 'Never') ? now()->diffInDays(\Carbon\Carbon::parse($lastUpdate)) : 'N/A';
        $daysSinceBackup = ($lastBackup !== 'Never') ? now()->diffInDays(\Carbon\Carbon::parse($lastBackup)) : 'N/A';
        
        // Create a combined message for the card
        $systemInfo = "Update: {$daysSinceUpdate} days ago, Backup: {$daysSinceBackup} days ago";
        
        // Progress for system info (based on days since last backup, capped at 30 days = 100%)
        $systemProgress = min(100, ($daysSinceBackup !== 'N/A') ? $daysSinceBackup * 3.33 : 50); // 3.33% per day, capped at 100%
        
        // Calculate net cash flow for current month
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        
        // --- CACHED ---
        // Get total payments for current month
        $totalPayments = Cache::remember("admin_stats_total_payments_month", $cacheTime, function () use ($startDate, $endDate) {
            return Payment::where('status', 'paid')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');
        });
        
        // --- CACHED ---
        // Get total disbursements for current month
        $totalDisbursements = Cache::remember("admin_stats_total_disbursements_month", $cacheTime, function () use ($startDate, $endDate) {
            return Disbursement::where('status', 'processed')
                ->whereBetween('disbursement_date', [$startDate, $endDate])
                ->sum('amount');
        });
        
        // Calculate net cash flow
        $netCashFlow = $totalPayments - $totalDisbursements;
        
        // Format the net cash flow for display
        $formattedNetCashFlow = number_format($netCashFlow, 2);
        $formattedTotalPayments = number_format($totalPayments, 2);
        $formattedTotalDisbursements = number_format($totalDisbursements, 2);
        
        // --- CACHED ---
        // Get recent appointments (limit 5) with patient and doctor information
        $recentAppointments = Cache::remember("admin_stats_recent_appointments", $cacheTime, function () {
            return Appointment::with(['patient', 'doctor'])
                ->whereHas('patient')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        });
        
        // --- CACHED ---
        // Get available doctors for assignment
        $availableDoctors = Cache::remember("admin_stats_available_doctors", $cacheTime, function () {
            return User::where('role', 'doctor')
                ->where('status', 'verified')
                ->get();
        });
        
        // --- CACHED ---
        // Get new patients (limit 5)
        $newPatients = Cache::remember("admin_stats_new_patients_list", $cacheTime, function () {
            return User::where('role', 'patient')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        });
        
        return view('admin.index', compact(
            'totalUsers', 
            'progressPercentage', 
            'actualPercentage', 
            'newRegistrations', 
            'newRegProgress', 
            'regChangePercentage',
            'pendingAppointments',
            'pendingProgress',
            'pendingChangePercentage',
            'pendingInvitations', // <-- ADDED THIS VARIABLE
            'systemInfo',
            'systemProgress',
            'formattedNetCashFlow',
            'totalPayments',
            'totalDisbursements',
            'formattedTotalPayments',
            'formattedTotalDisbursements',
            'recentAppointments',
            'availableDoctors',
            'newPatients'
        ));
    }
}
