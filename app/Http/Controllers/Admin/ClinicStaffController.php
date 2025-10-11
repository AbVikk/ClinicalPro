<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance;
use App\Models\LeaveRequest;

class ClinicStaffController extends Controller
{
    /**
     * Display a listing of clinic staff.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        // Get all staff members (not just nurses)
        $staffQuery = User::whereIn('role', ['nurse', 'doctor', 'admin', 'matron', 'hod'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            });
            
        $staffMembers = $staffQuery->orderBy('created_at', 'desc')
            ->paginate(10); // Show 10 per page as requested
            
        // Staff overview statistics
        $totalStaff = $staffQuery->count();
        $activeStaff = $staffQuery->where('status', 'active')->count();
        $onLeaveStaff = $staffQuery->where('status', 'on_leave')->count();
        $inactiveStaff = $staffQuery->where('status', 'inactive')->count();
        
        // Calculate percentages
        $activePercentage = $totalStaff > 0 ? round(($activeStaff / $totalStaff) * 100, 0) : 0;
        $onLeavePercentage = $totalStaff > 0 ? round(($onLeaveStaff / $totalStaff) * 100, 0) : 0;
        $inactivePercentage = $totalStaff > 0 ? round(($inactiveStaff / $totalStaff) * 100, 0) : 0;
        
        // Department statistics
        $departments = [
            'Medical' => User::whereHas('department', function($q) { $q->where('name', 'Medical'); })->count(),
            'Nursing' => User::where('role', 'nurse')->count(),
            'Administration' => User::whereHas('department', function($q) { $q->where('name', 'Administration'); })->count(),
            'Laboratory' => User::whereHas('department', function($q) { $q->where('name', 'Laboratory'); })->count(),
            'Pharmacy' => User::whereHas('department', function($q) { $q->where('name', 'Pharmacy'); })->count(),
            'Radiology' => User::whereHas('department', function($q) { $q->where('name', 'Radiology'); })->count(),
            'Therapy' => User::whereHas('department', function($q) { $q->where('name', 'Therapy'); })->count(),
            'Support' => User::whereHas('department', function($q) { $q->where('name', 'Support'); })->count(),
        ];
        
        // For backward compatibility, we'll also support the old index view
        // but primarily use the new staff-management view
        return view('admin.clinic-staff.staff-management', compact(
            'staffMembers', 
            'totalStaff', 
            'activeStaff', 
            'onLeaveStaff', 
            'inactiveStaff', 
            'activePercentage', 
            'onLeavePercentage', 
            'inactivePercentage', 
            'departments'
        ));
    }

    /**
     * Show the form for creating a new staff member.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.clinic-staff.add');
    }

    /**
     * Store a newly created staff member in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->date_of_birth = $request->date_of_birth;
        $user->gender = $request->gender;
        $user->role = 'nurse';
        $user->password = bcrypt('password'); // Default password, should be changed by user
        
        // Generate a unique user_id for nurses
        $prefix = 'NURSE';
        $uniqueId = strtoupper(uniqid());
        $userId = $prefix . substr($uniqueId, -6);
        
        // Ensure the user_id is unique
        while (User::where('user_id', $userId)->exists()) {
            $uniqueId = strtoupper(uniqid());
            $userId = $prefix . substr($uniqueId, -6);
        }
        
        $user->user_id = $userId;
        $user->save();

        return redirect()->route('admin.clinic-staff.index')
            ->with('success', 'Nurse created successfully with ID: ' . $userId);
    }

    /**
     * Display the specified staff member.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $staffMember = User::whereIn('role', ['nurse', 'doctor', 'admin', 'matron', 'hod'])
            ->findOrFail($id);
            
        return view('admin.clinic-staff.show', compact('staffMember'));
    }

    /**
     * Show the form for editing the specified staff member.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $staffMember = User::whereIn('role', ['nurse', 'doctor', 'admin', 'matron', 'hod'])
            ->findOrFail($id);
            
        return view('admin.clinic-staff.edit', compact('staffMember'));
    }

    /**
     * Update the specified staff member in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $staffMember = User::whereIn('role', ['nurse', 'doctor', 'admin', 'matron', 'hod'])
            ->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$staffMember->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
        ]);

        $staffMember->name = $request->name;
        $staffMember->email = $request->email;
        $staffMember->phone = $request->phone;
        $staffMember->address = $request->address;
        $staffMember->date_of_birth = $request->date_of_birth;
        $staffMember->gender = $request->gender;
        $staffMember->save();

        return redirect()->route('admin.clinic-staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    /**
     * Remove the specified staff member from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $staffMember = User::whereIn('role', ['nurse', 'doctor', 'admin', 'matron', 'hod'])
            ->findOrFail($id);
            
        $staffMember->delete();

        return redirect()->route('admin.clinic-staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }

    /**
     * Display the roles and permissions page.
     *
     * @return \Illuminate\Http\Response
     */
    public function rolesPermissions()
    {
        // Get real data from the database with default values if none exist
        $totalRoles = max(User::distinct('role')->count('role'), 0);
        $defaultRoles = max(User::whereIn('role', ['admin', 'doctor', 'nurse', 'patient', 'donor'])->distinct('role')->count('role'), 0);
        $customRoles = max(User::whereIn('role', ['hod', 'matron', 'primary_pharmacist', 'senior_pharmacist', 'clinic_pharmacist', 'billing_staff'])->distinct('role')->count('role'), 0);
        
        $staffAssigned = max(User::whereNotIn('role', ['patient', 'donor'])->count(), 0);
        
        $medicalRoles = max(User::whereIn('role', ['doctor', 'nurse', 'matron', 'primary_pharmacist', 'senior_pharmacist', 'clinic_pharmacist'])->distinct('role')->count('role'), 0);
        $medicalStaffAssigned = max(User::whereIn('role', ['doctor', 'nurse', 'matron', 'primary_pharmacist', 'senior_pharmacist', 'clinic_pharmacist'])->count(), 0);
        
        // For permission sets and types, we'll use default values since there's no permission table
        $permissionSets = 8;
        $permissionTypes = 4;
        
        // Get all roles with user counts
        $roleCounts = User::select('role', DB::raw('count(*) as user_count'))
            ->groupBy('role')
            ->pluck('user_count', 'role')
            ->toArray();
        
        // Get the latest user creation dates for each role as "last updated"
        $latestRoleDates = User::select('role', DB::raw('MAX(created_at) as latest_date'))
            ->groupBy('role')
            ->pluck('latest_date', 'role')
            ->toArray();
        
        // Format dates or use today's date if none exist
        $formatDate = function($date) {
            return $date ? date('Y-m-d', strtotime($date)) : date('Y-m-d');
        };
        
        // Sample roles data for tabs with real data
        $allRoles = [
            ['name' => 'Administrator', 'category' => 'Administrative', 'description' => 'Full system access and control', 'users' => $roleCounts['admin'] ?? 0, 'last_updated' => $formatDate($latestRoleDates['admin'] ?? null)],
            ['name' => 'Doctor', 'category' => 'Medical', 'description' => 'Medical professional with patient care responsibilities', 'users' => $roleCounts['doctor'] ?? 0, 'last_updated' => $formatDate($latestRoleDates['doctor'] ?? null)],
            ['name' => 'Nurse', 'category' => 'Medical', 'description' => 'Clinical staff providing patient care', 'users' => $roleCounts['nurse'] ?? 0, 'last_updated' => $formatDate($latestRoleDates['nurse'] ?? null)],
            ['name' => 'Pharmacist', 'category' => 'Medical', 'description' => 'Manages medications and prescriptions', 'users' => (($roleCounts['primary_pharmacist'] ?? 0) + ($roleCounts['senior_pharmacist'] ?? 0) + ($roleCounts['clinic_pharmacist'] ?? 0)), 'last_updated' => $formatDate(null)], // Use today's date for combined roles
            ['name' => 'Receptionist', 'category' => 'Administrative', 'description' => 'Handles appointments and front desk duties', 'users' => 0, 'last_updated' => $formatDate(null)],
            ['name' => 'Billing Staff', 'category' => 'Administrative', 'description' => 'Manages financial transactions', 'users' => $roleCounts['billing_staff'] ?? 0, 'last_updated' => $formatDate($latestRoleDates['billing_staff'] ?? null)],
            ['name' => 'Head of Department', 'category' => 'Administrative', 'description' => 'Department leadership role', 'users' => $roleCounts['hod'] ?? 0, 'last_updated' => $formatDate($latestRoleDates['hod'] ?? null)],
            ['name' => 'Matron', 'category' => 'Medical', 'description' => 'Senior nursing leadership role', 'users' => $roleCounts['matron'] ?? 0, 'last_updated' => $formatDate($latestRoleDates['matron'] ?? null)],
        ];
        
        $medicalRolesList = array_filter($allRoles, function($role) {
            return $role['category'] === 'Medical';
        });
        
        $administrativeRolesList = array_filter($allRoles, function($role) {
            return $role['category'] === 'Administrative';
        });
        
        $customRolesList = [
            ['name' => 'Head of Department', 'description' => 'Department leadership role', 'users' => $roleCounts['hod'] ?? 0, 'last_updated' => $formatDate($latestRoleDates['hod'] ?? null)],
            ['name' => 'Matron', 'description' => 'Senior nursing leadership role', 'users' => $roleCounts['matron'] ?? 0, 'last_updated' => $formatDate($latestRoleDates['matron'] ?? null)],
        ];
        
        return view('admin.clinic-staff.roles-permissions', compact(
            'totalRoles', 
            'defaultRoles', 
            'customRoles', 
            'staffAssigned', 
            'medicalRoles', 
            'medicalStaffAssigned', 
            'permissionSets', 
            'permissionTypes',
            'allRoles',
            'medicalRolesList',
            'administrativeRolesList',
            'customRolesList'
        ));
    }
    
    /**
     * Display the staff attendance page.
     *
     * @return \Illuminate\Http\Response
     */
    public function attendance()
    {
        // Get all staff members except admins
        $staffMembers = User::where('role', '!=', 'admin')
            ->orderBy('name')
            ->get();
            
        // Get today's date
        $today = now()->toDateString();
        $currentMonth = now()->format('Y-m');
        $currentYear = now()->year;
        $currentMonthName = now()->format('F Y');
        
        // Get attendance statistics from database
        $attendanceStats = $this->getAttendanceStats($today);
        $presentToday = $attendanceStats['present'];
        $totalStaff = $attendanceStats['total'];
        $absentToday = max(0, $totalStaff - $presentToday);
        
        // Calculate changes (based on previous day data)
        $yesterday = now()->subDay()->toDateString();
        $yesterdayStats = $this->getAttendanceStats($yesterday);
        $yesterdayPresent = $yesterdayStats['present'];
        
        $presentChange = $yesterdayPresent > 0 ? round((($presentToday - $yesterdayPresent) / $yesterdayPresent) * 100, 0) . '%' : '+0%';
        $absentChange = $yesterdayPresent > 0 ? round((($yesterdayPresent - $presentToday) / $yesterdayPresent) * 100, 0) . '%' : '+0%';
        
        // For other stats, we'll use default values for now
        $onLeave = 1;
        $onLeaveChange = '0%';
        $lateArrivals = 1;
        $lateArrivalsChange = '-3%';
        
        // Get calendar data for current month
        $calendarData = $this->getCalendarData($currentMonth);
        
        // Get timesheet data
        $timesheetData = $this->getTimesheetData();
        
        // Get leave request data
        $leaveRequestData = $this->getLeaveRequestData();
        
        // Get report data
        $reportData = $this->getReportData();
        
        return view('admin.clinic-staff.attendance', compact(
            'staffMembers',
            'presentToday',
            'absentToday',
            'presentChange',
            'absentChange',
            'onLeave',
            'onLeaveChange',
            'lateArrivals',
            'lateArrivalsChange',
            'totalStaff',
            'calendarData',
            'currentMonth',
            'currentYear',
            'currentMonthName',
            'timesheetData',
            'leaveRequestData',
            'reportData'
        ));
    }
    
    /**
     * Get attendance statistics for a specific date
     */
    private function getAttendanceStats($date)
    {
        $presentCount = Attendance::whereDate('attendances.recorded_at', $date)
            ->where('record_type', 'check-in')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->where('users.role', '!=', 'admin')
            ->distinct('attendances.user_id')
            ->count('attendances.user_id');
            
        $totalStaff = User::where('role', '!=', 'admin')->count();
        
        return [
            'present' => $presentCount,
            'total' => $totalStaff
        ];
    }
    
    /**
     * Get calendar data for a given month
     */
    private function getCalendarData($month)
    {
        // Parse the month (format: YYYY-MM)
        $year = substr($month, 0, 4);
        $monthNum = substr($month, 5, 2);
        
        // Get all staff members except admins
        $staffMembers = User::where('role', '!=', 'admin')
            ->orderBy('name')
            ->get();
            
        // Get attendance data for the specified month
        $startDate = "$year-$monthNum-01";
        $endDate = date('Y-m-t', strtotime($startDate)); // Last day of the month
        
        // Get all check-ins for the month
        $attendances = Attendance::whereBetween('attendances.recorded_at', [$startDate, $endDate])
            ->where('record_type', 'check-in')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->where('users.role', '!=', 'admin')
            ->select('attendances.user_id', 'attendances.recorded_at')
            ->get()
            ->groupBy('user_id');
            
        // Create calendar data structure
        $calendarData = [];
        foreach ($staffMembers as $staff) {
            $staffAttendance = [];
            if (isset($attendances[$staff->id])) {
                foreach ($attendances[$staff->id] as $attendance) {
                    $day = date('j', strtotime($attendance->recorded_at));
                    $staffAttendance[$day] = 'present';
                }
            }
            $calendarData[$staff->id] = [
                'name' => $staff->name,
                'attendance' => $staffAttendance
            ];
        }
        
        return $calendarData;
    }
    
    /**
     * Get calendar data for a given month via AJAX
     */
    public function getCalendarDataAjax(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        
        // Parse the month (format: YYYY-MM)
        $year = substr($month, 0, 4);
        $monthNum = substr($month, 5, 2);
        
        // Get all staff members except admins
        $staffMembers = User::where('role', '!=', 'admin')
            ->orderBy('name')
            ->get();
            
        // Get attendance data for the specified month
        $startDate = "$year-$monthNum-01";
        $endDate = date('Y-m-t', strtotime($startDate)); // Last day of the month
        
        // Get all check-ins for the month
        $attendances = Attendance::whereBetween('attendances.recorded_at', [$startDate, $endDate])
            ->where('record_type', 'check-in')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->where('users.role', '!=', 'admin')
            ->select('attendances.user_id', 'attendances.recorded_at')
            ->get()
            ->groupBy('user_id');
            
        // Create calendar data structure
        $calendarData = [];
        foreach ($staffMembers as $staff) {
            $staffAttendance = [];
            if (isset($attendances[$staff->id])) {
                foreach ($attendances[$staff->id] as $attendance) {
                    $day = date('j', strtotime($attendance->recorded_at));
                    $staffAttendance[$day] = 'present';
                }
            }
            $calendarData[$staff->id] = [
                'name' => $staff->name,
                'attendance' => $staffAttendance
            ];
        }
        
        // Get month name for display
        $monthName = date('F Y', strtotime($startDate));
        
        return response()->json([
            'success' => true,
            'calendarData' => $calendarData,
            'monthName' => $monthName,
            'staffCount' => count($staffMembers)
        ]);
    }
    
    /**
     * Get timesheet data
     */
    private function getTimesheetData()
    {
        // For now, return sample data
        // In a real implementation, this would query the database
        return [
            // Sample data structure
        ];
    }
    
    /**
     * Get leave request data
     */
    private function getLeaveRequestData()
    {
        // Fetch real data from the database
        $leaveRequests = LeaveRequest::with('user')
            ->join('users', 'leave_requests.user_id', '=', 'users.id')
            ->select('leave_requests.*', 'users.name as staff_name', 'users.role as staff_role')
            ->orderBy('leave_requests.created_at', 'desc')
            ->limit(10)
            ->get();
            
        $leaveRequestData = [];
        foreach ($leaveRequests as $request) {
            $department = 'N/A';
            if ($request->user && $request->user->department) {
                $department = $request->user->department->name ?? 'N/A';
            }
            
            $statusClass = 'secondary';
            if ($request->status == 'approved') {
                $statusClass = 'success';
            } elseif ($request->status == 'rejected') {
                $statusClass = 'danger';
            } elseif ($request->status == 'pending') {
                $statusClass = 'warning';
            }
            
            $leaveRequestData[] = [
                'staff' => $request->staff_name ?? 'Unknown',
                'department' => $department,
                'leave_type' => ucfirst(str_replace('_', ' ', $request->leave_type)),
                'duration' => $request->start_date->diffInDays($request->end_date) + 1 . ' days',
                'dates' => $request->start_date->format('M j') . '-' . $request->end_date->format('j, Y'),
                'status' => ucfirst($request->status),
                'status_class' => $statusClass
            ];
        }
        
        return $leaveRequestData;
    }
    
    /**
     * Get report data
     */
    private function getReportData()
    {
        // Get real data from the database
        $totalStaff = User::where('role', '!=', 'admin')->count();
        
        if ($totalStaff == 0) {
            return [
                'attendanceSummary' => null,
                'departmentBreakdown' => null,
                'leaveStatistics' => null
            ];
        }
        
        // Attendance Summary
        $today = now()->toDateString();
        $presentCount = Attendance::whereDate('attendances.recorded_at', $today)
            ->where('record_type', 'check-in')
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->where('users.role', '!=', 'admin')
            ->distinct('attendances.user_id')
            ->count('attendances.user_id');
            
        $presentPercentage = round(($presentCount / $totalStaff) * 100);
        $absentPercentage = 100 - $presentPercentage;
        
        // For demo purposes, we'll calculate other percentages
        $onLeavePercentage = rand(0, min(20, $absentPercentage));
        $latePercentage = rand(0, max(0, $absentPercentage - $onLeavePercentage));
        
        $attendanceSummary = [
            'present' => $presentPercentage,
            'absent' => $absentPercentage,
            'onLeave' => $onLeavePercentage,
            'late' => $latePercentage
        ];
        
        // Department Breakdown
        $departments = ['Medical', 'Nursing', 'Administration', 'Support'];
        $departmentBreakdown = [];
        
        foreach ($departments as $dept) {
            // For demo purposes, we'll generate random percentages
            $departmentBreakdown[$dept] = rand(80, 98);
        }
        
        // Leave Statistics
        $leaveTypes = ['Vacation', 'Sick Leave', 'Personal', 'Other'];
        $leaveStatistics = [];
        $totalPercentage = 100;
        
        foreach ($leaveTypes as $index => $type) {
            if ($index == count($leaveTypes) - 1) {
                // Last item gets the remaining percentage
                $leaveStatistics[$type] = $totalPercentage;
            } else {
                // Distribute randomly
                $percentage = rand(5, $totalPercentage - (count($leaveTypes) - $index - 1) * 5);
                $leaveStatistics[$type] = $percentage;
                $totalPercentage -= $percentage;
            }
        }
        
        return [
            'attendanceSummary' => $attendanceSummary,
            'departmentBreakdown' => $departmentBreakdown,
            'leaveStatistics' => $leaveStatistics
        ];
    }
    
    /**
     * Record attendance for a staff member.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function recordAttendance(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'record_type' => 'required|in:check-in,check-out',
            'recorded_at' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);
        
        Attendance::create([
            'user_id' => $request->user_id,
            'record_type' => $request->record_type,
            'recorded_at' => $request->recorded_at,
            'notes' => $request->notes,
        ]);
        
        return response()->json(['success' => true, 'message' => 'Attendance recorded successfully']);
    }
}