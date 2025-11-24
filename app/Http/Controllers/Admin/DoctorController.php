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
use App\Models\Clinic;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\ManagesAdminCache;

class DoctorController extends Controller
{
    use ManagesAdminCache; // <-- 2. USE THE HELPER

    /**
     * Display a listing of the doctors.
     */
    public function index(Request $request)
    {
        // (This function remains the same)
        $query = Doctor::with(['user', 'category', 'appointments.patient']);
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }
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
        // (This function remains the same)
        $query = User::where('role', 'hod')
                     ->with(['doctor.department']);
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
        if ($user->role !== 'hod') {
            return redirect()->back()->with('error', 'User is not an HOD.');
        }

        try {
            $user->update(['role' => 'doctor']);
            $department = Department::where('department_head_id', $user->id)->first();
            if ($department) {
                $department->update(['department_head_id' => null]);
            }

            // --- 3. THIS IS THE UPGRADE ---
            // Use our new helper function
            $this->flushAdminStatsCache();
            // --- END OF UPGRADE ---

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
        // (This function remains the same)
        $departments = Department::all();
        $categories = Category::all();
        return view('admin.doctor.add_doctor', compact('departments', 'categories'));
    }

    /**
     * Store a newly created doctor in storage.
     */
    public function store(Request $request)
    {
        // (This function remains the same)
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'license_number' => 'required|string|max:100',
            'specialization_id' => 'required|exists:categories,id',
            'department_id' => 'required|exists:departments,id',
            'password' => 'required|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        \Log::info('Doctor store request data:', $request->all());

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role' => 'doctor',
        ]);

        \Log::info('Created user:', $user->toArray());

        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $imageName = time() . '_' . $profileImage->getClientOriginalName();
            $profileImage->storeAs('public/profile_images', $imageName);
            $user->photo = 'profile_images/' . $imageName;
            $user->save();
        }

        $doctor = Doctor::create([
            'user_id' => $user->id,
            'doctor_id' => 'DOC' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
            'license_number' => $request->license_number,
            'category_id' => $request->specialization_id,
            'department_id' => $request->department_id,
            'status' => $request->status ?? 'verified',
            'availability' => json_encode([]), 
        ]);

        \Log::info('Created doctor:', $doctor->toArray());

        // --- 3. THIS IS THE UPGRADE ---
        // Use our new helper function
        $this->flushAdminStatsCache();
        // --- END OF UPGRADE ---

        return redirect()->route('admin.doctor.index')->with('success', 'Doctor added successfully.');
    }

    /**
     * Display the doctor dashboard.
     */
    public function dashboard()
    {
        // (This function remains the same)
        $today = now()->toDateString();
        $doctorsCount = Doctor::count();
        $appointmentsCount = Appointment::whereDate('appointment_time', $today)->count();
        $patientsCount = User::where('role', 'patient')->count();
        $disbursementsCount = Payment::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)
                                        ->count();
        $todaysAppointments = Appointment::with(['patient', 'doctor'])
                                            ->whereDate('appointment_time', $today)
                                            ->orderBy('appointment_time')
                                            ->limit(5)
                                            ->get();
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
        // (This function remains the same)
        $doctor->load(['user', 'department', 'category', 'appointments.patient']);
        $consultations = \App\Models\Consultation::with(['patient'])
            ->where('doctor_id', $doctor->user_id)
            ->orderBy('start_time', 'desc')
            ->get();
        $doctorSchedule = $doctor->schedules()->with('clinic')->get()->groupBy('day_of_week');
        $todayName = now()->format('l'); 
        $todayKey = strtolower($todayName);  
        $todaysSchedule = $doctorSchedule->get($todayKey); 
        $todaysAppointments = \App\Models\Appointment::with(['patient', 'consultation'])
            ->where('doctor_id', $doctor->user_id)
            ->whereDate('appointment_time', now())
            ->orderBy('appointment_time', 'asc')
            ->get();
        
        return view('admin.doctor.profile', compact(
            'doctor', 
            'consultations', 
            'doctorSchedule',
            'todayName',
            'todaysSchedule',
            'todaysAppointments' 
        ));
    }

    /**
     * Show the form for editing the specified doctor.
     */
    public function edit(Doctor $doctor)
    {
        // (This function remains the same)
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
        if ($request->has('status') && in_array($request->status, ['suspended', 'verified'])) {
            $doctor->update([
                'status' => $request->status,
            ]);
            
            // --- 3. THIS IS THE UPGRADE ---
            $this->flushAdminStatsCache();
            // --- END OF UPGRADE ---

            return redirect()->route('admin.doctor.index')->with('success', 'Doctor status updated successfully.');
        }
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $doctor->user_id,
            'license_number' => 'required|string|max:100',
            'specialization_id' => 'required|exists:categories,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        $doctor->user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
        ]);

        if ($request->hasFile('profile_image')) {
            $profileImage = $request->file('profile_image');
            $imageName = time() . '_' . $profileImage->getClientOriginalName();
            $profileImage->storeAs('public/profile_images', $imageName);
            $doctor->user->photo = 'profile_images/' . $imageName;
            $doctor->user->save();
        }

        $doctor->update([
            'license_number' => $request->license_number,
            'category_id' => $request->specialization_id,
            'department_id' => $request->department_id,
            'status' => $request->status,
        ]);

        // --- 3. THIS IS THE UPGRADE ---
        $this->flushAdminStatsCache();
        // --- END OF UPGRADE ---

        return redirect()->route('admin.doctor.index')->with('success', 'Doctor updated successfully.');
    }

    /**
     * Remove the specified doctor from storage.
     */
    public function destroy(Doctor $doctor)
    {
        try {
            $doctor->user->delete();
            
            // --- 3. THIS IS THE UPGRADE ---
            $this->flushAdminStatsCache();
            // --- END OF UPGRADE ---

            return redirect()->route('admin.doctor.index')->with('success', 'Doctor deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.doctor.index')->with('error', 'Failed to delete doctor: ' . $e->getMessage());
        }
    }

    /**
     * Show the doctor specialization management page.
     */
    public function specializations()
    {
        // (This function remains the same)
        $categories = Category::all();
        $departments = Department::all();
        $specializations = collect();
        foreach ($categories as $category) {
            $specializations->push([
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'doctors_count' => $category->doctors()->count(),
                'type' => 'Category'
            ]);
        }
        foreach ($departments as $department) {
            $specializations->push([
                'id' => $department->id,
                'name' => $department->name,
                'description' => $department->description,
                'doctors_count' => $department->doctors()->count(),
                'type' => 'Department'
            ]);
        }
        return view('admin.doctor.specialization.specializations', compact('specializations'));
    }

    /**
     * Show the doctor schedule management page.
     */
    public function schedule()
    {
        // (This function remains the same)
        $doctor = Auth::user()->doctor;
        if (!$doctor) {
            return redirect()->route('admin.doctor.index')->with('error', 'You are not a doctor.');
        }
        $schedules = $doctor->schedules;
        return view('admin.doctor.schedule', compact('doctor', 'schedules'));
    }

    /**
     * Store a new doctor schedule.
     */
    public function storeSchedule(Request $request)
    {
        // (This function remains the same)
        $doctor = Auth::user()->doctor;
        if (!$doctor) {
            return redirect()->route('admin.doctor.index')->with('error', 'You are not a doctor.');
        }
        $request->validate([
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
        $doctor->schedules()->create([
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
        return redirect()->route('admin.doctor.schedule')->with('success', 'Schedule added successfully.');
    }

    /**
     * Update an existing doctor schedule.
     */
    public function updateSchedule(Request $request)
    {
        // (This function remains the same)
        $doctor = Auth::user()->doctor;
        if (!$doctor) {
            return redirect()->route('admin.doctor.index')->with('error', 'You are not a doctor.');
        }
        $request->validate([
            'schedule_id' => 'required|exists:doctor_schedules,id',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
        $schedule = $doctor->schedules()->findOrFail($request->schedule_id);
        $schedule->update([
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
        return redirect()->route('admin.doctor.schedule')->with('success', 'Schedule updated successfully.');
    }

    /**
     * Assign a user as Head of Department (HOD).
     */
    public function assignHOD(User $user)
    {
        if ($user->role !== 'doctor') {
            return redirect()->back()->with('error', 'User is not a doctor.');
        }
        try {
            $user->update(['role' => 'hod']);

            // --- 3. THIS IS THE UPGRADE ---
            $this->flushAdminStatsCache();
            // --- END OF UPGRADE ---

            return redirect()->back()->with('success', $user->name . ' has been successfully assigned as HOD.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to assign HOD: ' . $e->getMessage());
        }
    }
}