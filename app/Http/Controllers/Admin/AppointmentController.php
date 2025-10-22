<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\User;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Display the specified appointment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($id);
        
        return view('admin.appointment-show', compact('appointment'));
    }

    /**
     * Assign a doctor to an appointment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignDoctor(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $doctorId = $request->input('doctor_id');
        
        // Verify that the doctor exists and has the correct role
        $doctor = User::where('id', $doctorId)
            ->where('role', 'doctor')
            ->where('status', 'verified')
            ->first();
            
        if (!$doctor) {
            return response()->json(['error' => 'Invalid doctor selected'], 400);
        }
        
        $appointment->doctor_id = $doctorId;
        $appointment->save();
        
        // Create notification for the doctor
        $patient = $appointment->patient;
        if ($patient) {
            $message = "New appointment assigned: {$patient->name} scheduled for " . $appointment->appointment_time->format('M d, Y g:i A');
            \App\Models\Notification::create([
                'user_id' => $doctor->id,
                'type' => 'appointment',
                'message' => $message,
                'is_read' => false,
                'channel' => 'database', // Default channel for in-app notifications
            ]);
        }
        
        return response()->json(['success' => 'Doctor assigned successfully']);
    }

    /**
     * Update the specified appointment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        
        $oldStatus = $appointment->status;
        $appointment->update($request->only(['appointment_date', 'status', 'reason']));
        
        // Create notification for the patient if status changed to confirmed
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
                    'channel' => 'database', // Default channel for in-app notifications
                ]);
            }
        }
        
        return response()->json(['success' => 'Appointment updated successfully']);
    }

    /**
     * Remove the specified appointment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        
        return response()->json(['success' => 'Appointment deleted successfully']);
    }
}
