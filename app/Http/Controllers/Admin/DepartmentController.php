<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the departments.
     */
    public function index()
    {
        try {
            $departments = Department::withCount('doctorsInDepartment')->get();
            return view('admin.doctor.specialization.departments', compact('departments'))
                ->with('success', 'Departments loaded successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to load departments: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load departments. Please try again.');
        }
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        try {
            return view('admin.doctor.specialization.add_department')
                ->with('success', 'Department creation form loaded successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to load department creation form: ' . $e->getMessage());
            return redirect()->route('admin.doctor.specialization.departments')->with('error', 'Failed to load creation form. Please try again.');
        }
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:departments',
            ]);

            Department::create([
                'name' => $request->name,
            ]);

            return redirect()->route('admin.doctor.specialization.departments')->with('success', 'Department added successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to create department: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create department: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified department.
     */
    public function show(Department $department)
    {
        try {
            $department->load('doctors.user', 'head');
            
            // Calculate staff members count
            $doctorCount = $department->doctors()->count();
            $nurseCount = $department->clinicStaff()->count();
            $staffMembers = $doctorCount + $nurseCount;
            
            // Calculate services offered (categories count)
            $servicesOffered = \App\Models\Category::count();
            
            // Calculate monthly appointments (last 30 days)
            // Since appointments don't have a direct department_id, we'll count appointments
            // for doctors in this department
            $monthlyAppointments = \App\Models\Appointment::whereIn('doctor_id', 
                \App\Models\User::where('department_id', $department->id)->pluck('id'))
                ->where('created_at', '>=', now()->subDays(30))
                ->count();
            
            // Calculate growth values (comparing with previous month)
            $lastMonthAppointments = \App\Models\Appointment::whereIn('doctor_id', 
                \App\Models\User::where('department_id', $department->id)->pluck('id'))
                ->whereBetween('created_at', [now()->subMonths(2), now()->subMonths(1)])
                ->count();
                
            $lastMonthStaff = \App\Models\User::where('department_id', $department->id)
                ->where('created_at', '<', now()->subMonths(1))
                ->count();
                
            // Growth calculations
            $staffGrowth = max(0, $staffMembers - $lastMonthStaff);
            $serviceGrowth = 3; // Placeholder - would need historical data
            $appointmentGrowth = $lastMonthAppointments > 0 ? 
                round((($monthlyAppointments - $lastMonthAppointments) / $lastMonthAppointments) * 100, 1) : 0;
            
            // Capacity percentage (placeholder - would need actual capacity data)
            $capacityPercentage = 75;
            
            // Get department staff for the key staff tab
            $doctors = $department->doctors()->with('user')->get();
            $nurses = $department->clinicStaff()->get();
            
            // Get categories for services tab
            $categories = \App\Models\Category::limit(10)->get();
            
            return view('admin.doctor.specialization.department_show', compact(
                'department',
                'staffMembers',
                'servicesOffered',
                'monthlyAppointments',
                'staffGrowth',
                'serviceGrowth',
                'appointmentGrowth',
                'capacityPercentage',
                'doctors',
                'nurses',
                'categories'
            ))
                ->with('success', 'Department details loaded successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to load department details: ' . $e->getMessage());
            return redirect()->route('admin.doctor.specialization.departments')->with('error', 'Failed to load department details. Please try again.');
        }
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(Department $department)
    {
        try {
            // Get all doctors for the department head dropdown
            $doctors = User::where('role', 'doctor')->get();
            return view('admin.doctor.specialization.edit_department', compact('department', 'doctors'))
                ->with('success', 'Department edit form loaded successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to load department edit form: ' . $e->getMessage());
            return redirect()->route('admin.doctor.specialization.departments')->with('error', 'Failed to load edit form. Please try again.');
        }
    }

    /**
     * Update the specified department in storage.
     */
    public function update(Request $request, Department $department)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
                'head_id' => 'nullable|exists:users,id',
                'status' => 'nullable|in:active,inactive',
                'description' => 'nullable|string|max:500',
                'about' => 'nullable|string',
                'history' => 'nullable|string',
                'goals' => 'nullable|string',
                'location' => 'nullable|string|max:255',
                'contact' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
            ]);

            $department->update([
                'name' => $request->name,
                'head_id' => $request->head_id,
                'status' => $request->status,
                'description' => $request->description,
                'about' => $request->about,
                'history' => $request->history,
                'goals' => $request->goals,
                'location' => $request->location,
                'contact' => $request->contact,
                'email' => $request->email,
            ]);

            return redirect()->route('admin.doctor.specialization.departments')->with('success', 'Department updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update department: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update department: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy(Department $department)
    {
        try {
            $department->delete();
            return redirect()->route('admin.doctor.specialization.departments')->with('success', 'Department deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete department: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete department: ' . $e->getMessage());
        }
    }
}