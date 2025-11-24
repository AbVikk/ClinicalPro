<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;

class CheckInController extends Controller
{
    /**
     * Show the check-in queue page.
     */
    public function index()
    {
        // This page shows patients who are PHYSICAL
        // and have been CONFIRMED by the doctor.
        $patientsWaiting = Appointment::with('patient', 'doctor', 'payment')
            ->where('type', 'in_person') // Only physical appointments
            ->where('status', 'approved') // Only ones the doctor approved
            ->orderBy('appointment_time', 'asc')
            ->get();
            
        return view('admin.checkin.index', compact('patientsWaiting'));
    }

    /**
     * Process the patient check-in.
     */
    public function checkInPatient(Request $request, Appointment $appointment)
    {
        // This function moves the patient from the Admin queue
        // to the Nurse's queue by changing the status.
        
        // You can add payment confirmation logic here
        
        $appointment->status = 'checked_in';
        $appointment->save();
        
        // You could fire an alert to the Nurse role here
        
        return redirect()->route('admin.checkin.index')
                         ->with('success', 'Patient checked in. Now waiting for Nurse vitals.');
    }
}