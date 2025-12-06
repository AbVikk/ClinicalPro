<?php

namespace App\Http\Controllers\Pharmacy\PrimaryPharmacist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prescription;
use App\Models\PrescriptionItem;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of prescriptions.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Build the base query for prescriptions
        $baseQuery = Prescription::with(['patient', 'doctor', 'items.drug'])
            ->where('hospital_id', $user->hospital_id); // FIX: Scope to hospital
        
        // Apply search filter if provided
        $search = $request->get('search');
        if ($search) {
            $baseQuery->where(function($query) use ($search) {
                $query->whereHas('patient', function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('user_id', 'LIKE', "%{$search}%");
                })->orWhereHas('doctor', function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })->orWhere('id', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply sorting
        $sort = $request->get('sort');
        if ($sort === 'patient') {
            $baseQuery->join('users as patients', 'prescriptions.patient_id', '=', 'patients.id')
                      ->orderBy('patients.name');
        } elseif ($sort === 'doctor') {
            $baseQuery->join('users as doctors', 'prescriptions.doctor_id', '=', 'doctors.id')
                      ->orderBy('doctors.name');
        } else {
            $baseQuery->orderBy('created_at', 'desc');
        }
        
        $prescriptions = $baseQuery->paginate(10)->appends(request()->query());
        
        return view('pharmacy.primary_pharmacist.prescriptions.index', compact('prescriptions', 'search', 'sort'));
    }
    
    /**
     * Display the specified prescription.
     */
    public function show(Prescription $prescription)
    {
        // Security check
        if ($prescription->hospital_id !== Auth::user()->hospital_id) abort(403);
        
        // Load all necessary relationships
        $prescription->load(['patient', 'items.drug', 'doctor.doctorProfile', 'consultation']);
        
        return view('pharmacy.primary_pharmacist.prescriptions.show', compact('prescription'));
    }
    
    /**
     * Generate PDF for a prescription
     */
    public function printPrescription($prescriptionId)
    {
        $prescription = Prescription::with(['patient', 'doctor.doctorProfile', 'items.drug', 'consultation'])
            ->where('hospital_id', Auth::user()->hospital_id) // FIX: Scope to hospital
            ->findOrFail($prescriptionId);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.prescription', compact('prescription'));
        
        // Download the file with a nice name like "Prescription-JohnDoe-2024.pdf"
        $filename = 'Prescription-' . \Illuminate\Support\Str::slug($prescription->patient->name) . '-' . now()->format('Ymd') . '.pdf';
        
        return $pdf->download($filename);
    }
}