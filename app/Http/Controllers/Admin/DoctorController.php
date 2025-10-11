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
            'status' => 'nullable|string|in:active,inactive,suspended',
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
        // If it's a status update request
        if ($request->has('status')) {
            $request->validate([
                'status' => 'required|in:verified,suspended',
            ]);

            $doctor->update([
                'status' => $request->status,
            ]);

            return redirect()->route('admin.doctor.index')->with('success', 'Doctor status updated successfully.');
        }

        // If it's a full doctor update
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
            'status' => $request->status ?? $doctor->status,
        ]);

        return redirect()->route('admin.doctor.profile', $doctor->user_id)->with('success', 'Doctor updated successfully.');
    }

    /**
     * Remove the specified doctor from storage.
     */
    public function destroy(Doctor $doctor)
    {
        // Delete the associated user as well
        $doctor->user->delete();
        $doctor->delete();

        return redirect()->route('admin.doctor.index')->with('success', 'Doctor deleted successfully.');
    }

    /**
     * Display the doctor schedule.
     */
    public function schedule()
    {
        try {
            // Get future appointments with patient and doctor information (next 30 days)
            $appointments = \App\Models\Appointment::with(['patient', 'doctor'])
                ->where('appointment_time', '>=', now())
                ->where('appointment_time', '<=', now()->addDays(30))
                ->orderBy('appointment_time')
                ->get();
                
            // Get future consultations (new system) (next 30 days)
            $consultations = \App\Models\Consultation::with(['patient', 'doctor'])
                ->where('start_time', '>=', now())
                ->where('start_time', '<=', now()->addDays(30))
                ->orderBy('start_time')
                ->get();
            
            // Format appointments for FullCalendar
            $events = [];
            
            // Add old appointments
            foreach ($appointments as $appointment) {
                // Ensure we have valid data
                if ($appointment && $appointment->appointment_time) {
                    try {
                        $events[] = [
                            'title' => ($appointment->patient ? $appointment->patient->name : 'Unknown Patient') . ' - ' . ($appointment->type ?? 'Appointment'),
                            'start' => $appointment->appointment_time->format('Y-m-d\TH:i:s'),
                            'end' => $appointment->appointment_time->clone()->addHour()->format('Y-m-d\TH:i:s'),
                            'color' => '#007bff',
                            'type' => 'appointment',
                            'id' => $appointment->id
                        ];
                    } catch (\Exception $e) {
                        Log::warning('Error formatting appointment: ' . $e->getMessage());
                    }
                }
            }
            
            // Add new consultations
            foreach ($consultations as $consultation) {
                // Ensure we have valid data
                if ($consultation && $consultation->start_time) {
                    try {
                        $events[] = [
                            'title' => ($consultation->patient ? $consultation->patient->name : 'Unknown Patient') . ' - ' . ($consultation->service_type ?? 'Consultation'),
                            'start' => $consultation->start_time->format('Y-m-d\TH:i:s'),
                            'end' => $consultation->end_time ? $consultation->end_time->format('Y-m-d\TH:i:s') : $consultation->start_time->clone()->addHour()->format('Y-m-d\TH:i:s'),
                            'color' => '#28a745',
                            'type' => 'consultation',
                            'id' => $consultation->id
                        ];
                    } catch (\Exception $e) {
                        Log::warning('Error formatting consultation: ' . $e->getMessage());
                    }
                }
            }
            
            // Get doctor schedules for the edit modal
            $doctor = Doctor::with('schedules')->first(); // In a real app, you might want to filter by specific doctor
            $schedules = $doctor ? $doctor->schedules : collect();
            
            // Debug log
            Log::info('Schedule events data:', ['events_count' => count($events), 'events' => $events]);
            
            return view('admin.doctor.schedule', compact('events', 'schedules', 'doctor'));
        } catch (\Exception $e) {
            Log::error('Error in schedule method: ' . $e->getMessage());
            return view('admin.doctor.schedule', [
                'events' => [], 
                'schedules' => collect(), 
                'doctor' => null
            ]);
        }
    }
    
    /**
     * Display the doctor specializations.
     */
    public function specializations()
    {
        // Get categories with doctor counts
        $categories = Category::withCount('doctors')->get()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'doctors_count' => $category->doctors_count,
                'type' => 'Category'
            ];
        });

        // Get departments with doctor counts
        $departments = Department::withCount('doctors')->get()->map(function ($department) {
            return [
                'id' => $department->id,
                'name' => $department->name,
                'description' => null,
                'doctors_count' => $department->doctors_count,
                'type' => 'Department'
            ];
        });

        // Merge categories 
        $specializations = $categories->merge($departments);

        return view('admin.doctor.specialization.specializations', compact('specializations'));
    }
    
    /**
     * Store a newly created schedule in storage.
     */
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors_new,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'service_type' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // For now, we'll just return a success response
        // In a real implementation, you would store this in a proper table
        return response()->json(['success' => true, 'message' => 'Schedule added successfully']);
    }
    
    /**
     * Update the specified doctor's schedule.
     */
    public function updateSchedule(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors_new,id',
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'schedules.*.is_available' => 'required|boolean',
            'schedules.*.start_time' => 'nullable|date_format:H:i',
            'schedules.*.end_time' => 'nullable|date_format:H:i',
        ]);
        
        $doctorId = $request->input('doctor_id');
        
        // Delete existing schedules for this doctor
        DoctorSchedule::where('doctor_id', $doctorId)->delete();
        
        // Create new schedules
        foreach ($request->input('schedules') as $scheduleData) {
            DoctorSchedule::create([
                'doctor_id' => $doctorId,
                'day_of_week' => $scheduleData['day_of_week'],
                'is_available' => $scheduleData['is_available'],
                'start_time' => $scheduleData['is_available'] ? $scheduleData['start_time'] : null,
                'end_time' => $scheduleData['is_available'] ? $scheduleData['end_time'] : null,
            ]);
        }
        
        return response()->json(['success' => true, 'message' => 'Schedule updated successfully']);
    }
}