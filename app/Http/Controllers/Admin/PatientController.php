<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Cache; // <-- ADD THIS "WHISTLEBLOWER" IMPORT

class PatientController extends Controller
{
    /**
     * Display a listing of patients.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // For live search, we'll load all patients but still support server-side search for pagination
        $query = User::where('role', 'patient');
        
        // Add server-side search functionality (for initial page load or when search is submitted)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }
        
        // We'll still paginate for initial load, but the live search will filter client-side
        $patients = $query->orderBy('created_at', 'desc')
            ->paginate(100) // Load more patients for better live search experience
            ->appends(['search' => $request->search]);
        
        return view('admin.patients.index', compact('patients'));
    }

    /**
     * Display the specified patient.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $patient = User::where('role', 'patient')
            ->with([
                'appointmentsAsPatient.doctor', 
                'prescriptions.items.drug', 
                'prescriptions.doctor'
            ]) // Eager load relationships
            ->findOrFail($id);
            
        // Get recent appointments for this patient (limit 3)
        $recentAppointments = Appointment::where('patient_id', $patient->id)
            ->with(['doctor', 'appointmentReason'])
            ->orderBy('appointment_time', 'desc')
            ->limit(3)
            ->get();
            
        // Get all appointments for this patient
        $allAppointments = Appointment::where('patient_id', $patient->id)
            ->with(['doctor', 'doctor.doctor', 'appointmentReason'])
            ->orderBy('appointment_time', 'desc')
            ->get();
        
        return view('admin.patients.show', compact('patient', 'recentAppointments', 'allAppointments'));
    }

    /**
     * Remove the specified patient from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $patient = User::where('role', 'patient')
            ->findOrFail($id);
        
        // Delete the patient
        $patient->delete();
        
        // --- THIS IS THE "WHISTLEBLOWER" ---
        // A user (a patient) was deleted. Erase all user/patient-related "whiteboard" answers!
        Cache::forget("admin_stats_total_users");
        Cache::forget("admin_stats_new_registrations_7d");
        Cache::forget("admin_stats_prev_week_registrations");
        Cache::forget("admin_stats_new_patients_list");
        // --- END OF WHISTLEBLOWER ---

        return redirect()->route('patients.index')
            ->with('success', 'Patient deleted successfully.');
    }
}