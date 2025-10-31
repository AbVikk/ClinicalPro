<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClinicInventory;
use App\Models\PharmacyOrder;
use App\Models\Payment;
use App\Models\Prescription;
use Illuminate\Support\Facades\Cache; // <-- ADD THIS "WHISTLEBLOWER" IMPORT

class ClinicPharmacistController extends Controller
{
    /**
     * Process in-clinic sale
     */
    public function sell(Request $request)
    {
        $user = Auth::user();
        
        // Ensure user has a clinic assigned
        if (!$user->clinic_id) {
            return response()->json(['error' => 'User not assigned to a clinic'], 400);
        }

        $request->validate([
            'drug_id' => 'required|exists:drugs,id',
            'quantity' => 'required|integer|min:1',
            'patient_id' => 'nullable|exists:users,id',
            'prescription_id' => 'nullable|exists:prescriptions,id',
            'is_controlled' => 'boolean',
        ]);

        // Check if it's a controlled drug
        $drug = \App\Models\Drug::findOrFail($request->input('drug_id'));
        
        if ($drug->is_controlled) {
            // For controlled drugs, verify an active prescriptions record exists
            if (!$request->has('prescription_id')) {
                return response()->json(['error' => 'Prescription required for controlled drugs'], 400);
            }
            
            $prescription = Prescription::where('id', $request->input('prescription_id'))
                ->where('status', 'active')
                ->first();
                
            if (!$prescription) {
                return response()->json(['error' => 'Valid active prescription required for controlled drugs'], 400);
            }
        }

        // Check inventory availability
        $inventory = ClinicInventory::where('clinic_id', $user->clinic_id)
            ->whereHas('batch', function($query) use ($request) {
                $query->where('drug_id', $request->input('drug_id'));
            })
            ->first();
            
        if (!$inventory || $inventory->stock_level < $request->input('quantity')) {
            return response()->json(['error' => 'Insufficient stock available'], 400);
        }

        // Deduct sold quantity from the local clinic_inventories.stock_level
        $inventory->stock_level -= $request->input('quantity');
        $inventory->save();

        // Create a pharmacy_orders record
        $order = new PharmacyOrder();
        $order->prescription_id = $request->input('prescription_id');
        $order->patient_id = $request->input('patient_id', $user->id); // Default to pharmacist if no patient specified
        $order->clinic_id = $user->clinic_id;
        $order->total_amount = $drug->unit_price * $request->input('quantity');
        $order->status = 'completed';
        $order->save();

        // Create a payments record
        $payment = new Payment();
        $payment->user_id = $request->input('patient_id', $user->id);
        $payment->order_id = $order->id;
        $payment->clinic_id = $user->clinic_id;
        $payment->amount = $order->total_amount;
        $payment->method = 'cash_in_clinic';
        $payment->status = 'paid';
        $payment->reference = 'PHARM-' . $order->id . '-' . time();
        $payment->transaction_date = now();
        $payment->save();

        // If this was for a prescription, update the fulfillment status
        if ($request->has('prescription_id')) {
            $prescriptionItem = \App\Models\PrescriptionItem::where('prescription_id', $request->input('prescription_id'))
                ->where('drug_id', $request->input('drug_id'))
                ->first();
                
            if ($prescriptionItem) {
                $prescriptionItem->fulfillment_status = 'purchased';
                $prescriptionItem->save();
                
                // Check if all items in the prescription are fulfilled
                $allFulfilled = \App\Models\PrescriptionItem::where('prescription_id', $request->input('prescription_id'))
                    ->where('fulfillment_status', '!=', 'purchased')
                    ->where('fulfillment_status', '!=', 'dispensed')
                    ->count() === 0;
                    
                if ($allFulfilled) {
                    $prescription = Prescription::findOrFail($request->input('prescription_id'));
                    $prescription->status = 'filled';
                    $prescription->save();
                }
            }
        }

        // --- THIS IS THE "WHISTLEBLOWER" ---
        // A new payment was just made, so the dashboard's "Total Payments" is wrong.
        // Erase the old answer so it recalculates! This will also fix "Net Cash Flow".
        Cache::forget("admin_stats_total_payments_month");
        // --- END OF WHISTLEBLOWER ---

        return response()->json([
            'message' => 'Sale processed successfully',
            'order' => $order,
            'payment' => $payment
        ], 201);
    }
}
