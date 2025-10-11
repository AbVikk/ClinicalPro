<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockTransfer;
use App\Models\ClinicInventory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SeniorPharmacistController extends Controller
{
    /**
     * Request stock from central warehouse
     */
    public function requestStock(Request $request)
    {
        $user = Auth::user();
        
        // Ensure user has a clinic assigned
        if (!$user->clinic_id) {
            return response()->json(['error' => 'User not assigned to a clinic'], 400);
        }

        $request->validate([
            'batch_id' => 'required|exists:drug_batches,id',
            'quantity' => 'required|integer|min:1',
            'warehouse_id' => 'required|exists:clinics,id,is_warehouse,1',
        ]);

        // Find the central warehouse
        $warehouse = \App\Models\Clinic::where('id', $request->input('warehouse_id'))
            ->where('is_warehouse', true)
            ->first();
            
        if (!$warehouse) {
            return response()->json(['error' => 'Invalid warehouse specified'], 400);
        }

        // Create a record in stock_transfers
        $transfer = new StockTransfer();
        $transfer->batch_id = $request->input('batch_id');
        $transfer->source_id = $warehouse->id; // Central Warehouse ID
        $transfer->destination_id = $user->clinic_id; // Current clinic ID
        $transfer->quantity = $request->input('quantity');
        $transfer->status = 'requested';
        $transfer->save();

        return response()->json([
            'message' => 'Stock request submitted successfully',
            'transfer' => $transfer
        ], 201);
    }

    /**
     * Receive stock from transfer
     */
    public function receiveStock($id)
    {
        $user = Auth::user();
        
        // Ensure user has a clinic assigned
        if (!$user->clinic_id) {
            return response()->json(['error' => 'User not assigned to a clinic'], 400);
        }

        $transfer = StockTransfer::findOrFail($id);
        
        // Check if transfer is destined for this clinic
        if ($transfer->destination_id !== $user->clinic_id) {
            return response()->json(['error' => 'This transfer is not destined for your clinic'], 403);
        }
        
        // Check if transfer is in shipped status
        if ($transfer->status !== 'shipped') {
            return response()->json(['error' => 'Transfer is not in shipped status'], 400);
        }

        // Increment stock_level in clinic_inventories for their assigned clinic_id and the received batch
        $inventory = ClinicInventory::firstOrNew([
            'batch_id' => $transfer->batch_id,
            'clinic_id' => $user->clinic_id,
        ]);
        
        $inventory->stock_level = $inventory->stock_level + $transfer->quantity;
        $inventory->save();

        // Set stock_transfers.status to 'received'
        $transfer->status = 'received';
        $transfer->save();

        return response()->json([
            'message' => 'Stock received successfully',
            'transfer' => $transfer
        ]);
    }

    /**
     * Get low stock alerts
     */
    public function getLowStockAlerts()
    {
        $user = Auth::user();
        
        // Ensure user has a clinic assigned
        if (!$user->clinic_id) {
            return response()->json(['error' => 'User not assigned to a clinic'], 400);
        }

        // Display clinic_inventories records where stock_level <= reorder_point for their assigned clinic_id
        $lowStockItems = ClinicInventory::with(['batch.drug'])
            ->where('clinic_id', $user->clinic_id)
            ->whereColumn('stock_level', '<=', 'reorder_point')
            ->get();

        return response()->json([
            'low_stock_items' => $lowStockItems
        ]);
    }
}