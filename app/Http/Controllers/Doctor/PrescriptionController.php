<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prescription;
use App\Models\PrescriptionItem;

class PrescriptionController extends Controller
{
    /**
     * Issue a new prescription
     */
    public function prescribe(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'consultation_id' => 'nullable|exists:consultations,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.drug_id' => 'required|exists:drugs,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.dosage_instructions' => 'required|string',
        ]);

        // Create a prescriptions record with status='active'
        $prescription = new Prescription();
        $prescription->patient_id = $request->input('patient_id');
        $prescription->doctor_id = $user->id;
        $prescription->consultation_id = $request->input('consultation_id');
        $prescription->status = 'active';
        $prescription->notes = $request->input('notes');
        $prescription->save();

        // Create associated prescription_items
        $items = [];
        foreach ($request->input('items') as $itemData) {
            $item = new PrescriptionItem();
            $item->prescription_id = $prescription->id;
            $item->drug_id = $itemData['drug_id'];
            $item->quantity = $itemData['quantity'];
            $item->dosage_instructions = $itemData['dosage_instructions'];
            $item->fulfillment_status = 'pending';
            $item->save();
            $items[] = $item;
        }

        return response()->json([
            'message' => 'Prescription issued successfully',
            'prescription' => $prescription,
            'items' => $items
        ], 201);
    }

    /**
     * Check fulfillment status of a prescription
     */
    public function checkFulfillment($id)
    {
        $user = Auth::user();
        
        $prescription = Prescription::with('items')
            ->where('id', $id)
            ->where('doctor_id', $user->id)
            ->firstOrFail();

        return response()->json([
            'prescription' => $prescription
        ]);
    }
}