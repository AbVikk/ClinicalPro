<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prescription;
use App\Models\Drug;

class PwaPharmacyController extends Controller
{
    /**
     * Display the PWA pharmacy view
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get active prescriptions for the patient
        $activePrescriptions = Prescription::with(['doctor', 'items.drug'])
            ->where('patient_id', $user->id)
            ->where('status', 'active')
            ->get();
            
        // Get OTC drugs (non-controlled)
        $otcDrugs = Drug::where('is_controlled', false)->get();

        return response()->json([
            'active_prescriptions' => $activePrescriptions,
            'otc_drugs' => $otcDrugs
        ]);
    }

    /**
     * Search for drugs in PWA
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        $query = $request->input('query', '');
        
        // Enforcement: Search results are always restricted to drugs.is_controlled = false
        $drugs = Drug::where('is_controlled', false)
            ->where(function($q) use ($query) {
                if ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('category', 'LIKE', "%{$query}%");
                }
            })
            ->get();

        return response()->json([
            'drugs' => $drugs
        ]);
    }
}