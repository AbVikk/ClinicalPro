<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Prescription;
use App\Models\PrescriptionItem;

class PrescriptionController extends Controller
{
    /**
     * Issue a new prescription with transaction safety.
     * If any item fails to save, the entire prescription is rolled back.
     */
    public function prescribe(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'consultation_id' => 'nullable|exists:consultations,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.drug_id' => 'required|exists:drugs,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.dosage_instructions' => 'required|string',
        ]);

        try {
            // Start the transaction
            $result = DB::transaction(function () use ($validated, $user) {
                
                // 1. Create the Prescription Header
                $prescription = Prescription::create([
                    'patient_id' => $validated['patient_id'],
                    'doctor_id' => $user->id,
                    'consultation_id' => $validated['consultation_id'] ?? null,
                    'status' => 'active',
                    'notes' => $validated['notes'] ?? null,
                ]);

                $items = [];
                
                // 2. Create each Prescription Item
                foreach ($validated['items'] as $itemData) {
                    $item = PrescriptionItem::create([
                        'prescription_id' => $prescription->id,
                        'drug_id' => $itemData['drug_id'],
                        'quantity' => $itemData['quantity'],
                        'dosage_instructions' => $itemData['dosage_instructions'],
                        'fulfillment_status' => 'pending',
                    ]);
                    $items[] = $item;
                }

                return [
                    'prescription' => $prescription,
                    'items' => $items
                ];
            });

            return response()->json([
                'message' => 'Prescription issued successfully',
                'prescription' => $result['prescription'],
                'items' => $result['items']
            ], 201);

        } catch (\Exception $e) {
            Log::error("[PrescriptionController] Failed to issue prescription: " . $e->getMessage());
            return response()->json(['error' => 'Failed to issue prescription. Please try again.'], 500);
        }
    }

    /**
     * Check fulfillment status of a prescription.
     */
    public function checkFulfillment($id)
    {
        $user = Auth::user();
        
        $prescription = Prescription::with('items.drug')
            ->where('id', $id)
            ->where('doctor_id', $user->id)
            ->firstOrFail();

        return response()->json([
            'prescription' => $prescription
        ]);
    }
}