<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Department;
use App\Models\Category;
use App\Models\DoctorSchedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    /**
     * Display a listing of the doctors.
     */
    public function index(Request $request)
    {
        // Start with a base query
        $query = Doctor::with(['user', 'category', 'appointments.patient']);
        
        // Filter by category if provided
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filter by department if provided
        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        
        // Add search functionality
        $search = $request->get('search');
        if ($search) {
            $query->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $doctors = $query->get();
        return view('admin.doctor.doctors_lists', compact('doctors'));
    }

    /**
     * Display a listing of all HODs.
     */
    public function listHODs(Request $request)
    {
        // Get all HODs with their departments
        $query = User::where('role', 'hod')
                    ->with(['doctor.department']);
        
        // Add search functionality
        $search = $request->get('search');
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }
        
        $hods = $query->get();
        return view('admin.doctor.hods_list', compact('hods'));
    }

    /**
     * Assign an HOD back to doctor role.
     */
    public function assignDoctorRole(User $user)
    {
        // Check if the user is an HOD
        if ($user->role !== 'hod') {
            return redirect()->back()->with('error', 'User is not an HOD.');
        }

        try {
            // Update the user's role to doctor
            $user->update([
                'role' => 'doctor'
            ]);

            // Remove the user as department head if they were one
            $department = Department::where('department_head_id', $user->id)->first();
            if ($department) {
                $department->update([
                    'department_head_id' => null
                ]);
            }

            return redirect()->back()->with('success', $user->name . ' has been successfully assigned back to doctor role.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to assign doctor role: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new doctor.
     */
    public function create()
    {
        $departments = Department::all();
        $categories = Category::all();
        return view('admin.doctor.add_doctor', compact('departments', 'categories'));
    }

    /**
     * Store a newly created doctor in storage.
     */
    public function store(Request $request)
    {
        // Validation rules
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'license_number' => 'required|string|max:100',
            'specialization_id' => 'required|exists:categories,id',
            'department_id' => 'required|exists:departments,id',
            'medical_school' => 'nullable|string|max:255',
            'residency' => 'nullable|string|max:255',
            'fellowship' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:active,inactive,suspended,verified',
            'bio' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role' => 'doctor',
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $imageName = time() . '_' . $profileImage->getClientOriginalName();
            $profileImage->storeAs('public/profile_images', $imageName);
            $user->photo = 'profile_images/' . $imageName;
            $user->save();
        }

        // Create doctor record
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'license_number' => $request->license_number,
            'category_id' => $request->specialization_id,
            'department_id' => $request->department_id,
            'status' => $request->status ?? 'verified',
            'availability' => json_encode([]), // Empty availability by default
        ]);

        return redirect()->route('admin.doctor.index')->with('success', 'Doctor added successfully.');
    }

    /**
     * Display the doctor dashboard.
     */
    public function dashboard()
    {
        // Get today's date
        $today = now()->toDateString();
        
        // Get all doctors count
        $doctorsCount = Doctor::count();
        
        // Get today's appointments for all doctors
        $appointmentsCount = Appointment::whereDate('appointment_time', $today)->count();
        
        // Get all patients (users with role 'patient')
        $patientsCount = User::where('role', 'patient')->count();
        
        // Get this month's payments (disbursements)
        $disbursementsCount = Payment::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count();
        
        // Get today's appointments with patient and doctor information
        $todaysAppointments = Appointment::with(['patient', 'doctor'])
                                        ->whereDate('appointment_time', $today)
                                        ->orderBy('appointment_time')
                                        ->limit(5)
                                        ->get();
        
        // Pass data to the view
        return view('admin.doctor.index', compact(
            'doctorsCount',
            'appointmentsCount',
            'patientsCount',
            'disbursementsCount',
            'todaysAppointments'
        ));
    }

    /**
     * Display the specified doctor profile.
     */
    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'department', 'category', 'appointments.patient']);
        
        // Also load consultations for the new system
        $consultations = \App\Models\Consultation::with(['patient'])
            ->where('doctor_id', $doctor->user_id)
            ->orderBy('start_time', 'desc')
            ->get();
        
        return view('admin.doctor.profile', compact('doctor', 'consultations'));
    }

    /**
     * Show the form for editing the specified doctor.
     */
    public function edit(Doctor $doctor)
    {
        $doctor->load(['user', 'department', 'category']);
        $departments = Department::all();
        $categories = Category::all();
        return view('admin.doctor.add_doctor', compact('doctor', 'departments', 'categories'));
    }

    /**
     * Update the specified doctor in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        // If this is a status update (suspend/activate), handle it differently
        if ($request->has('status') && in_array($request->status, ['suspended', 'verified'])) {
            // Just update the doctor's status
            $doctor->update([
                'status' => $request->status,
            ]);
            
            return redirect()->route('admin.doctor.index')->with('success', 'Doctor status updated successfully.');
        }
        
        // Full update (when editing doctor details)
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $doctor->user_id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'license_number' => 'required|string|max:100',
            'specialization_id' => 'required|exists:categories,id',
            'department_id' => 'required|exists:departments,id',
            'medical_school' => 'nullable|string|max:255',
            'residency' => 'nullable|string|max:255',
            'fellowship' => 'nullable|string|max:255',
            'years_of_experience' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:active,inactive,suspended,verified',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user
        $doctor->user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $imageName = time() . '_' . $profileImage->getClientOriginalName();
            $profileImage->storeAs('public/profile_images', $imageName);
            $doctor->user->photo = 'profile_images/' . $imageName;
            $doctor->user->save();
        }

        // Update doctor record
        $doctor->update([
            'license_number' => $request->license_number,
            'category_id' => $request->specialization_id,
            'department_id' => $request->department_id,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.doctor.index')->with('success', 'Doctor updated successfully.');
    }

    /**
     * Assign a doctor as Head of Department (HOD)
     */
    public function assignHOD(User $user)
    {
        // Check if the user is a doctor
        if ($user->role !== 'doctor') {
            return redirect()->back()->with('error', 'Only doctors can be assigned as HOD.');
        }

        // Get the doctor's department
        $doctor = $user->doctor;
        if (!$doctor || !$doctor->department_id) {
            return redirect()->back()->with('error', 'Doctor must be assigned to a department before becoming HOD.');
        }

        try {
            // Update the user's role to HOD
            $user->update([
                'role' => 'hod'
            ]);

            // Assign the user as the department head
            $department = Department::find($doctor->department_id);
            if ($department) {
                $department->update([
                    'department_head_id' => $user->id
                ]);
            }

            return redirect()->back()->with('success', $user->name . ' has been successfully assigned as HOD.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to assign HOD: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified doctor from storage.
     */
    public function destroy(Doctor $doctor)
    {
        try {
            // Delete the doctor record
            $doctor->delete();
            
            // Optionally delete the associated user (be careful with this)
            // $doctor->user->delete();
            
            return redirect()->route('admin.doctor.index')->with('success', 'Doctor deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete doctor: ' . $e->getMessage());
        }
    }

    /**
     * Show the doctor schedule management page.
     */
    public function schedule()
    {
        $doctor = Auth::user()->doctor;
        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor profile not found.');
        }
        
        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)->get();
        return view('admin.doctor.schedule', compact('schedules', 'doctor'));
    }

    /**
     * Store a newly created schedule in storage.
     */
    public function storeSchedule(Request $request)
    {
        $doctor = Auth::user()->doctor;
        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor profile not found.');
        }
        
        $request->validate([
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
        
        DoctorSchedule::create([
            'doctor_id' => $doctor->id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
        
        return redirect()->back()->with('success', 'Schedule added successfully.');
    }

    /**
     * Update the specified schedule in storage.
     */
    public function updateSchedule(Request $request)
    {
        $doctor = Auth::user()->doctor;
        if (!$doctor) {
            return redirect()->back()->with('error', 'Doctor profile not found.');
        }
        
        $request->validate([
            'schedule_id' => 'required|exists:doctor_schedules,id',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
        
        $schedule = DoctorSchedule::where('id', $request->schedule_id)
                                  ->where('doctor_id', $doctor->id)
                                  ->firstOrFail();
        
        $schedule->update([
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
        
        return redirect()->back()->with('success', 'Schedule updated successfully.');
    }

    /**
     * Show all specializations.
     */
    public function specializations()
    {
        $departments = Department::with('categories')->get();
        return view('admin.doctor.specialization.specializations', compact('departments'));
    }
}