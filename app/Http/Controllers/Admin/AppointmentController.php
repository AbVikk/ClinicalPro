<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;
use App\Traits\ManagesAdminCache;

class AppointmentController extends Controller
{
    use ManagesAdminCache; // <-- 2. USE THE HELPER

    /**
     * Display a listing of appointments.
     */
    public function index()
    {
        // (This function remains the same)
        $appointments = Appointment::with(['patient', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Display the specified appointment.
     */
    public function show($id)
    {
        // (This function remains the same)
        $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($id);
        
        return view('admin.appointment-show', compact('appointment'));
    }

    /**
     * Assign a doctor to an appointment.
     */
    public function assignDoctor(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $doctorId = $request->input('doctor_id');
        
        $doctor = User::where('id', $doctorId)
            ->where('role', 'doctor')
            // ->where('status', 'verified') // Status check removed to allow any active doc
            ->first();
            
        if (!$doctor) {
            return response()->json(['error' => 'Invalid doctor selected'], 400);
        }
        
        $appointment->doctor_id = $doctorId;
        $appointment->save();
        
        // --- 3. THIS IS THE UPGRADE ---
        $this->flushAdminStatsCache();
        // --- END OF UPGRADE ---

        $patient = $appointment->patient;
        if ($patient) {
            $message = "New appointment assigned: {$patient->name} scheduled for " . $appointment->appointment_time->format('M d, Y g:i A');
            \App\Models\Notification::create([
                'user_id' => $doctor->id,
                'type' => 'appointment',
                'message' => $message,
                'is_read' => false,
                'channel' => 'database',
            ]);
        }
        
        return response()->json(['success' => 'Doctor assigned successfully']);
    }

    /**
     * Update the specified appointment.
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        
        $oldStatus = $appointment->status;
        $appointment->update($request->only(['appointment_date', 'status', 'reason']));
        
        // --- 3. THIS IS THE UPGRADE ---
        $this->flushAdminStatsCache();
        // --- END OF UPGRADE ---

        if ($oldStatus != 'confirmed' && $appointment->status == 'confirmed') {
            $doctor = $appointment->doctor;
            $patient = $appointment->patient;
            if ($doctor && $patient) {
                $message = "Your appointment with Dr. {$doctor->name} has been confirmed for " . $appointment->appointment_time->format('M d, Y g:i A');
                \App\Models\Notification::create([
                    'user_id' => $patient->id,
                    'type' => 'appointment',
                    'message' => $message,
                    'is_read' => false,
                    'channel' => 'database',
                ]);
            }
        }
        
        return response()->json(['success' => 'Appointment updated successfully']);
    }

    /**
     * Remove the specified appointment.
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        
        // --- 3. THIS IS THE UPGRADE ---
        $this->flushAdminStatsCache();
        // --- END OF UPGRADE ---
        
        return response()->json(['success' => 'Appointment deleted successfully']);
    }
}