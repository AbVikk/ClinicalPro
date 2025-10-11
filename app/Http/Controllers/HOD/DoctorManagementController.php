<?php

namespace App\Http\Controllers\HOD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class DoctorManagementController extends Controller
{
    /**
     * Display the HOD dashboard and list of doctors in their department.
     */
    public function index()
    {
        try {
            $hod = Auth::user();
            $department = $hod->department; // Get the HOD's department

            if (!$hod->department_id || !$department) {
                return view('hod.doctors.index', ['doctors' => collect([])])
                            ->with('error', 'You are not assigned to a department.');
            }

            // Doctors currently assigned to the HOD's department
            $doctors = $department->doctorsInDepartment()->paginate(15);
                           
            // Unassigned doctors (for assignment dropdown)
            $unassignedDoctors = User::where('role', User::ROLE_DOCTOR)
                                     ->whereNull('department_id')
                                     ->get();

            return view('hod.doctors.index', compact('doctors', 'unassignedDoctors', 'department', 'hod'))
                ->with('success', 'Doctors list loaded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load doctors list: ' . $e->getMessage());
        }
    }

    /**
     * HOD assigns an unassigned doctor to their department.
     */
    public function assignDoctor(Request $request)
    {
        try {
            $hod = Auth::user();
            
            $request->validate(['doctor_id' => 'required|exists:users,id']);

            if (!$hod->department_id) {
                return back()->with('error', 'Your HOD account is not linked to a department.');
            }

            $doctor = User::where('id', $request->doctor_id)
                          ->where('role', User::ROLE_DOCTOR)
                          ->whereNull('department_id') 
                          ->firstOrFail();

            $doctor->update(['department_id' => $hod->department_id]);

            return redirect()->route('hod.doctors.index')->with('success', $doctor->name . ' successfully assigned to your department.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to assign doctor: ' . $e->getMessage());
        }
    }

    /**
     * HOD removes a doctor from their department (sets department_id to null).
     */
    public function removeDoctor(Request $request)
    {
        try {
            $hod = Auth::user();
            
            $request->validate(['doctor_id' => 'required|exists:users,id']);
            
            $doctor = User::where('id', $request->doctor_id)
                          ->where('role', User::ROLE_DOCTOR)
                          ->where('department_id', $hod->department_id) // Must belong to HOD's dept
                          ->firstOrFail();

            $doctor->update(['department_id' => null]);

            return redirect()->route('hod.doctors.index')->with('success', $doctor->name . ' successfully unassigned from the department.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to remove doctor: ' . $e->getMessage());
        }
    }
}