<?php

namespace App\Http\Controllers\HOD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display the HOD dashboard.
     */
    public function index()
    {
        try {
            $hod = Auth::user();
            $department = $hod->department;

            // Count doctors and clinic staff in their department
            $doctorCount = $department ? $department->doctorsInDepartment()->count() : 0;
            $staffCount = $department ? $department->clinicStaff()->count() : 0;
            
            return view('hod.dashboard.index', compact('hod', 'department', 'doctorCount', 'staffCount'))
                ->with('success', 'Dashboard loaded successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to load HOD dashboard: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load dashboard: ' . $e->getMessage());
        }
    }
}