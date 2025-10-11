<?php

namespace App\Http\Controllers\Matron;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StaffManagementController extends Controller
{
    /**
     * Display the Matron dashboard and staff list.
     */
    public function index()
    {
        try {
            $matron = Auth::user();
            $department = $matron->department;
            
            if (!$department) {
                return view('matron.staff.index', ['staff' => collect([])])
                            ->with('error', 'You are not assigned to a department.');
            }

            // Clinic staff assigned to the Matron's department
            $staff = $department->clinicStaff()->paginate(15);
                           
            // Unassigned clinic staff (Nurses) for assignment
            $unassignedStaff = User::where('role', User::ROLE_NURSE)
                                     ->whereNull('department_id')
                                     ->get();
                                     
            return view('matron.staff.index', compact('matron', 'department', 'staff', 'unassignedStaff'))
                ->with('success', 'Staff list loaded successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to load staff list: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load staff list: ' . $e->getMessage());
        }
    }

    /**
     * Matron assigns an unassigned clinic staff (nurse) to their department.
     */
    public function assignStaff(Request $request)
    {
        try {
            $matron = Auth::user();
            
            $request->validate(['staff_id' => 'required|exists:users,id']);
            
            if (!$matron->department_id) {
                return back()->with('error', 'Your Matron account is not linked to a department.');
            }

            $staff = User::where('id', $request->staff_id)
                          ->where('role', User::ROLE_NURSE)
                          ->whereNull('department_id') 
                          ->firstOrFail();

            $staff->update(['department_id' => $matron->department_id]);

            return redirect()->route('matron.staff.index')->with('success', $staff->name . ' successfully assigned to the clinic staff team.');
        } catch (\Exception $e) {
            Log::error('Failed to assign staff: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to assign staff: ' . $e->getMessage());
        }
    }
    
    /**
     * Matron removes a staff member from their department (sets department_id to null).
     */
    public function removeStaff(Request $request)
    {
        try {
            $matron = Auth::user();
            
            $request->validate(['staff_id' => 'required|exists:users,id']);
            
            $staff = User::where('id', $request->staff_id)
                          ->where('role', User::ROLE_NURSE)
                          ->where('department_id', $matron->department_id) // Must belong to Matron's dept
                          ->firstOrFail();

            $staff->update(['department_id' => null]);

            return redirect()->route('matron.staff.index')->with('success', $staff->name . ' successfully unassigned from the department.');
        } catch (\Exception $e) {
            Log::error('Failed to remove staff: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to remove staff: ' . $e->getMessage());
        }
    }
}