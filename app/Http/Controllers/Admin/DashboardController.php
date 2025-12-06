<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Disbursement;
use App\Models\Invitation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Set our cache time. 3600 seconds = 1 hour.
        $cacheTime = 3600;
        
        // =================================================================
        // 1. KPI CARDS (Users, Registrations, Pending Actions)
        // =================================================================

        // Total Users (excluding admins)
        $totalUsers = Cache::remember("admin_stats_total_users", $cacheTime, function () {
            return User::where('role', '!=', 'admin')->count();
        });
        
        // Calculate progress percentage (visual only)
        $progressPercentage = min(100, floor($totalUsers / 20));
        $actualPercentage = min(100, ($totalUsers / 20) * 100);
        
        // New Registrations (Last 7 Days)
        $newRegistrations = Cache::remember("admin_stats_new_registrations_7d", $cacheTime, function () {
            return User::where('role', '!=', 'admin')
                ->where('created_at', '>=', now()->subDays(7))
                ->count();
        });
        $newRegProgress = min(100, $newRegistrations * 5);
        
        // Calculate change vs previous week
        $previousWeekRegistrations = Cache::remember("admin_stats_prev_week_registrations", $cacheTime, function () {
            return User::where('role', '!=', 'admin')
                ->whereBetween('created_at', [now()->subDays(14), now()->subDays(7)])
                ->count();
        });
            
        $regChangePercentage = 0;
        if ($previousWeekRegistrations > 0) {
            $regChangePercentage = round((($newRegistrations - $previousWeekRegistrations) / $previousWeekRegistrations) * 100);
        } elseif ($newRegistrations > 0) {
            $regChangePercentage = 100;
        }
        
        // Pending Appointments
        $pendingAppointments = Cache::remember("admin_stats_pending_appointments", $cacheTime, function () {
            return Appointment::where('status', 'pending')->count();
        });
        $pendingProgress = min(100, $pendingAppointments * 10);
        
        // Change vs previous week
        $previousWeekPending = Cache::remember("admin_stats_prev_week_pending", $cacheTime, function () {
            return Appointment::where('status', 'pending')
                ->whereBetween('created_at', [now()->subDays(14), now()->subDays(7)])
                ->count();
        });
            
        $pendingChangePercentage = 0;
        if ($previousWeekPending > 0) {
            $pendingChangePercentage = round((($pendingAppointments - $previousWeekPending) / $previousWeekPending) * 100);
        } elseif ($pendingAppointments > 0) {
            $pendingChangePercentage = 100;
        }
        
        // Pending Invitations
        $pendingInvitations = Cache::remember("admin_stats_pending_invitations", $cacheTime, function () {
            return Invitation::where('used', false)->where('expires_at', '>', now())->count();
        });

        // System Status (Fake data for UI visualization)
        $lastUpdate = Cache::get('system_last_update', 'Never');
        $lastBackup = Cache::get('system_last_backup', 'Never');
        
        if ($lastUpdate === 'Never') {
            $lastUpdate = now()->subDays(2)->format('Y-m-d H:i:s');
            Cache::put('system_last_update', $lastUpdate, now()->addDays(30));
        }
        if ($lastBackup === 'Never') {
            $lastBackup = now()->subDay()->format('Y-m-d H:i:s');
            Cache::put('system_last_backup', $lastBackup, now()->addDays(30));
        }
        
        $daysSinceUpdate = ($lastUpdate !== 'Never') ? now()->diffInDays(\Carbon\Carbon::parse($lastUpdate)) : 'N/A';
        $daysSinceBackup = ($lastBackup !== 'Never') ? now()->diffInDays(\Carbon\Carbon::parse($lastBackup)) : 'N/A';
        $systemInfo = "Update: {$daysSinceUpdate} days ago, Backup: {$daysSinceBackup} days ago";
        $systemProgress = min(100, ($daysSinceBackup !== 'N/A') ? $daysSinceBackup * 3.33 : 50);
        
        // =================================================================
        // 2. FINANCIAL OVERVIEW (This Month)
        // =================================================================
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        
        $totalPayments = Cache::remember("admin_stats_total_payments_month", $cacheTime, function () use ($startDate, $endDate) {
            return Payment::where('status', 'paid')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');
        });
        
        $totalDisbursements = Cache::remember("admin_stats_total_disbursements_month", $cacheTime, function () use ($startDate, $endDate) {
            return Disbursement::where('status', 'processed')
                ->whereBetween('disbursement_date', [$startDate, $endDate])
                ->sum('amount');
        });
        
        $netCashFlow = $totalPayments - $totalDisbursements;
        
        $formattedNetCashFlow = number_format($netCashFlow, 2);
        $formattedTotalPayments = number_format($totalPayments, 2);
        $formattedTotalDisbursements = number_format($totalDisbursements, 2);
        
        // =================================================================
        // 3. ANALYTICS CHARTS & STRATEGIC METRICS
        // =================================================================
        
        // A. Revenue Chart (Last 6 Months)
        $revenueData = Cache::remember("admin_chart_revenue", $cacheTime, function () {
            return Payment::select(
                DB::raw('SUM(amount) as total'),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
            )
            ->where('status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        });

        // B. Top Performing Doctors (By Appointments Count - Operational)
        $topDoctors = Cache::remember("admin_chart_doctors", $cacheTime, function () {
            return Appointment::select('doctor_id', DB::raw('count(*) as total'))
                ->where('status', 'completed')
                ->with('doctor')
                ->groupBy('doctor_id')
                ->orderByDesc('total')
                ->limit(5)
                ->get();
        });

        // C. Appointment Status Breakdown (Pie Chart)
        $appointmentStats = Cache::remember("admin_chart_status", $cacheTime, function () {
            return Appointment::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();
        });

        // --- NEW STRATEGIC METRICS (Owner View) ---

        // D. Revenue Per Doctor (Who brings in the money?)
        $revenuePerDoctor = Cache::remember("admin_stats_revenue_per_doctor", $cacheTime, function () {
            return DB::table('payments')
                ->join('consultations', 'payments.consultation_id', '=', 'consultations.id')
                ->join('users', 'consultations.doctor_id', '=', 'users.id')
                ->where('payments.status', 'paid')
                ->select('users.name as doctor_name', DB::raw('SUM(payments.amount) as total_revenue'))
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('total_revenue')
                ->limit(5)
                ->get();
        });

        // E. Busiest Time of Day (Heatmap Logic)
        $busiestHours = Cache::remember("admin_stats_busiest_hours", $cacheTime, function () {
            return Appointment::select(DB::raw('HOUR(appointment_time) as hour'), DB::raw('count(*) as count'))
                ->whereDate('appointment_time', '>=', now()->subDays(30))
                ->groupBy('hour')
                ->orderBy('hour')
                ->get()
                ->map(function ($item) {
                    return [
                        'hour' => Carbon::createFromTime($item->hour)->format('g A'),
                        'count' => $item->count
                    ];
                });
        });

        // F. No-Show Rate & Revenue Splits
        $metrics = Cache::remember("admin_stats_strategic_kpis", $cacheTime, function () {
            $total = Appointment::where('created_at', '>=', now()->subMonth())->count();
            $missed = Appointment::where('created_at', '>=', now()->subMonth())
                ->whereIn('status', ['missed', 'cancelled'])
                ->count();
            
            $pharmacyRev = DB::table('payments')
                ->where('status', 'paid')
                ->whereNotNull('order_id')
                ->sum('amount');
                
            $clinicRev = DB::table('payments')
                ->where('status', 'paid')
                ->whereNotNull('consultation_id')
                ->sum('amount');

            return [
                'noShowRate' => $total > 0 ? round(($missed / $total) * 100, 1) : 0,
                'pharmacyRevenue' => $pharmacyRev,
                'clinicRevenue' => $clinicRev
            ];
        });
        
        // =================================================================
        // 4. DATA LISTS (Tables)
        // =================================================================

        // Recent Appointments
        $recentAppointments = Cache::remember("admin_stats_recent_appointments", $cacheTime, function () {
            return Appointment::with(['patient', 'doctor'])
                ->whereHas('patient')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        });
        
        // Available Doctors (For assignment dropdowns)
        $availableDoctors = Cache::remember("admin_stats_available_doctors", $cacheTime, function () {
            return User::where('role', 'doctor')
                ->where('status', 'verified')
                ->get();
        });
        
        // New Patients List
        $newPatients = Cache::remember("admin_stats_new_patients_list", $cacheTime, function () {
            return User::where('role', 'patient')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        });
        
        // =================================================================
        // 5. RETURN VIEW
        // =================================================================
        return view('admin.index', compact(
            'totalUsers', 'progressPercentage', 'actualPercentage', 
            'newRegistrations', 'newRegProgress', 'regChangePercentage',
            'pendingAppointments', 'pendingProgress', 'pendingChangePercentage',
            'pendingInvitations', 'systemInfo', 'systemProgress',
            'formattedNetCashFlow', 'totalPayments', 'totalDisbursements',
            'formattedTotalPayments', 'formattedTotalDisbursements',
            'recentAppointments', 'availableDoctors', 'newPatients',
            // Charts & Strategic Data
            'revenueData', 'topDoctors', 'appointmentStats',
            'revenuePerDoctor', 'busiestHours', 'metrics' // <-- New Variables
        ));
    }
}