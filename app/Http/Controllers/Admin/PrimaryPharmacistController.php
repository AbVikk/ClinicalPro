<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Drug;
use App\Models\DrugBatch;
use App\Models\ClinicInventory;
use App\Models\StockTransfer;
use App\Models\Clinic;
use App\Models\DrugCategory;
use App\Models\DrugMg;

class PrimaryPharmacistController extends Controller
{
    /**
     * Show all drugs
     */
    public function showAllDrugs()
    {
        try {
            // FIX: Load BOTH 'batches' (History) and 'clinicInventories' (Live Stock)
            $allDrugs = Drug::with(['batches', 'clinicInventories'])->get();
            
            if (request()->routeIs('primary_pharmacist.pharmacy.drugs.*')) {
                return view('pharmacy.primary_pharmacist.pharmacy.drugs.all', compact('allDrugs'));
            }
            return view('admin.pharmacy.drugs.all', compact('allDrugs'));
        } catch (\Exception $e) {
            Log::error("Failed to load drugs: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load drugs.');
        }
    }

    /**
     * Show Create Form
     */
    public function showCreateDrugForm()
    {
        $categories = DrugCategory::all();
        $mgs = DrugMg::all();
        if (request()->routeIs('primary_pharmacist.pharmacy.drugs.*')) {
            return view('pharmacy.primary_pharmacist.pharmacy.drugs.create', compact('categories', 'mgs'));
        }
        return view('admin.pharmacy.drugs.create', compact('categories', 'mgs'));
    }

    /**
     * Create New Drug
     */
    public function createDrug(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required',
            'strength_mg' => 'required',
            'selling_price' => 'required|numeric',
            'initial_quantity' => 'required|integer|min:1',
            'expiry_date' => 'required|date',
            'medicine_image' => 'nullable|image|max:5120',
            'package_image' => 'nullable|image|max:5120',
        ]);

        return DB::transaction(function () use ($request) {
            try {
                // 1. Create Drug
                $drug = new Drug();
                $drug->name = $request->input('name');
                $drug->category = $request->input('category');
                $drug->strength_mg = $request->input('strength_mg');
                $drug->unit_price = $request->input('selling_price');
                $drug->is_controlled = $request->input('medicine_type') === 'Controlled';
                
                // 2. Prepare Details
                $details = $request->except([
                    '_token', 'name', 'category', 'strength_mg', 'selling_price', 
                    'initial_quantity', 'expiry_date', 'medicine_image', 'package_image',
                    'batch_number_inventory', 'purchase_price'
                ]);
                
                // 3. Handle Images
                if ($request->hasFile('medicine_image') && $request->file('medicine_image')->isValid()) {
                    $imageName = time() . '_med_' . uniqid() . '.' . $request->file('medicine_image')->extension();
                    $path = $request->file('medicine_image')->storeAs('drugs', $imageName, 'public');
                    $details['medicine_image'] = $path;
                }

                if ($request->hasFile('package_image') && $request->file('package_image')->isValid()) {
                    $imageName = time() . '_pkg_' . uniqid() . '.' . $request->file('package_image')->extension();
                    $path = $request->file('package_image')->storeAs('drugs', $imageName, 'public');
                    $details['package_image'] = $path;
                }
                
                $details['manufacturer'] = $request->input('manufacturer');
                $details['description'] = $request->input('description');
                $details['generic_name'] = $request->input('generic_name');
                $details['purchase_price'] = $request->input('purchase_price');
                
                $drug->details = $details;
                $drug->save();

                // 4. Handle Batch
                $batchUuid = $request->input('batch_number_inventory');
                if (empty($batchUuid)) {
                    $batchUuid = 'BATCH-' . strtoupper(Str::random(8));
                }
                if (DrugBatch::where('batch_uuid', $batchUuid)->exists()) {
                    $batchUuid = $batchUuid . '-' . strtoupper(Str::random(4));
                }

                $batch = new DrugBatch();
                $batch->batch_uuid = $batchUuid;
                $batch->drug_id = $drug->id;
                $batch->received_quantity = $request->input('initial_quantity');
                $batch->expiry_date = $request->input('expiry_date');
                $batch->cost_price = $request->input('purchase_price', 0);
                $batch->supplier_id = null; 
                $batch->save();

                // 5. Add to Warehouse Inventory
                $warehouse = Clinic::where('is_warehouse', true)->first();
                if (!$warehouse) {
                    $warehouse = Clinic::firstOrCreate(
                        ['is_warehouse' => true],
                        ['name' => 'Central Warehouse', 'address' => 'HQ', 'is_physical' => true]
                    );
                }

                $inventory = ClinicInventory::firstOrNew([
                    'batch_id' => $batch->id,
                    'clinic_id' => $warehouse->id,
                ]);
                
                $inventory->stock_level = ($inventory->stock_level ?? 0) + $request->input('initial_quantity');
                if ($request->has('reorder_level')) {
                    $inventory->reorder_point = $request->input('reorder_level');
                }
                $inventory->save();

                // 6. Redirect
                $route = request()->routeIs('primary_pharmacist.pharmacy.drugs.*') 
                    ? 'primary_pharmacist.pharmacy.drugs.all' 
                    : 'admin.pharmacy.drugs.all';
                
                return redirect()->route($route)
                    ->with('success', 'Drug "' . $drug->name . '" created successfully! Stock: ' . $request->input('initial_quantity'));

            } catch (\Exception $e) {
                Log::error("Create Drug Failed: " . $e->getMessage());
                throw $e; 
            }
        });
    }

    /**
     * Update Drug
     */
    public function updateDrug(Request $request, $id)
    {
        try {
            $drug = Drug::findOrFail($id);
            
            $request->validate([
                'name' => 'required|string',
                'unit_price' => 'required|numeric'
            ]);

            $drug->name = $request->input('name');
            $drug->category = $request->input('category');
            $drug->unit_price = $request->input('unit_price'); 
            if($request->has('medicine_type')) {
                $drug->is_controlled = $request->input('medicine_type') === 'Controlled';
            }

            $currentDetails = $drug->details ?? [];
            $newDetailsInput = $request->except(['_token', '_method', 'name', 'category', 'unit_price', 'medicine_image', 'package_image']);
            
            if ($request->hasFile('medicine_image') && $request->file('medicine_image')->isValid()) {
                if (!empty($currentDetails['medicine_image'])) {
                    Storage::disk('public')->delete($currentDetails['medicine_image']);
                }
                $imageName = time() . '_med_' . uniqid() . '.' . $request->file('medicine_image')->extension();
                $newDetailsInput['medicine_image'] = $request->file('medicine_image')->storeAs('drugs', $imageName, 'public');
            } else {
                $newDetailsInput['medicine_image'] = $currentDetails['medicine_image'] ?? null;
            }
            
            if ($request->hasFile('package_image') && $request->file('package_image')->isValid()) {
                if (!empty($currentDetails['package_image'])) {
                    Storage::disk('public')->delete($currentDetails['package_image']);
                }
                $imageName = time() . '_pkg_' . uniqid() . '.' . $request->file('package_image')->extension();
                $newDetailsInput['package_image'] = $request->file('package_image')->storeAs('drugs', $imageName, 'public');
            } else {
                $newDetailsInput['package_image'] = $currentDetails['package_image'] ?? null;
            }

            $drug->details = array_merge($currentDetails, $newDetailsInput);
            $drug->save();

            if (request()->routeIs('primary_pharmacist.pharmacy.drugs.*')) {
                return redirect()->route('primary_pharmacist.pharmacy.drugs.view', $id)
                    ->with('success', 'Drug updated successfully!');
            }
            return redirect()->route('admin.pharmacy.drugs.view', $id)
                ->with('success', 'Drug updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function viewDrug($id)
    {
        try {
            // FIX: Load BOTH batches and clinicInventories
            $drug = Drug::with(['batches', 'clinicInventories'])->findOrFail($id);
            
            $prescriptionItems = \App\Models\PrescriptionItem::where('drug_id', $id)
                ->with(['prescription.patient', 'prescription.doctor'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
                
            $alternatives = Drug::with(['batches', 'clinicInventories'])
                ->where('category', $drug->category)
                ->where('id', '!=', $id)
                ->limit(5)
                ->get();
            
            if (request()->routeIs('primary_pharmacist.pharmacy.drugs.*')) {
                return view('pharmacy.primary_pharmacist.pharmacy.drugs.view', compact('drug', 'prescriptionItems', 'alternatives'));
            }
            return view('admin.pharmacy.drugs.view', compact('drug', 'prescriptionItems', 'alternatives'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load drug details: ' . $e->getMessage());
        }
    }

    public function editDrug($id)
    {
        $drug = Drug::findOrFail($id);
        $categories = DrugCategory::all();
        $mgs = DrugMg::all();
        if (request()->routeIs('primary_pharmacist.pharmacy.drugs.*')) {
            return view('pharmacy.primary_pharmacist.pharmacy.drugs.edit', compact('drug', 'categories', 'mgs'));
        }
        return view('admin.pharmacy.drugs.edit', compact('drug', 'categories', 'mgs'));
    }

    public function deleteDrug($id)
    {
        try {
            $drug = Drug::findOrFail($id);
            if ($drug->prescriptionItems()->exists()) {
                return redirect()->back()->with('error', 'Cannot delete drug with existing prescriptions.');
            }
            foreach ($drug->batches as $batch) {
                $batch->clinicInventories()->delete();
                $batch->stockTransfers()->delete();
                $batch->delete();
            }
            $drug->delete();
            return redirect()->back()->with('success', 'Drug deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete drug.');
        }
    }

    /**
     * Receive bulk stock
     */
    public function receiveStock(Request $request)
    {
        $request->validate([
            'drug_id' => 'required|exists:drugs,id',
            'supplier_id' => 'nullable|exists:users,id',
            'received_quantity' => 'required|integer|min:1',
            'expiry_date' => 'required|date|after:today',
        ]);

        try {
            $batch = new DrugBatch();
            $batch->batch_uuid = (string) Str::uuid();
            $batch->drug_id = $request->input('drug_id');
            $batch->supplier_id = $request->input('supplier_id');
            $batch->received_quantity = $request->input('received_quantity');
            $batch->expiry_date = $request->input('expiry_date');
            $batch->save();

            $warehouse = Clinic::where('is_warehouse', true)->first();
            
            if (!$warehouse) {
                return response()->json(['error' => 'Central warehouse not found'], 404);
            }

            $inventory = ClinicInventory::firstOrNew([
                'batch_id' => $batch->id,
                'clinic_id' => $warehouse->id,
            ]);
            
            $inventory->stock_level = ($inventory->stock_level ?? 0) + $request->input('received_quantity');
            $inventory->save();

            return response()->json([
                'message' => 'Stock received successfully',
                'batch' => $batch
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to receive stock. Please try again.'], 500);
        }
    }

    /**
     * Update drug stock (Quick update)
     */
    public function updateStock(Request $request)
    {
        try {
            $request->validate([
                'drug_id' => 'required|exists:drugs,id',
                'action_type' => 'required|string|in:add,remove',
                'quantity' => 'required|integer|min:1',
                'batch_number' => 'required|string|max:255',
                'expiry_date' => 'required|date|after:today',
                'notes' => 'nullable|string',
            ]);

            $drug = Drug::findOrFail($request->input('drug_id'));
            $warehouse = Clinic::where('is_warehouse', true)->first();
            
            if (!$warehouse) {
                return response()->json(['error' => 'Central warehouse not found'], 404);
            }

            $batch = DrugBatch::firstOrNew([
                'batch_uuid' => $request->input('batch_number'),
                'drug_id' => $drug->id,
            ]);
            
            $batch->expiry_date = $request->input('expiry_date');
            
            if ($request->input('action_type') == 'add') {
                $batch->received_quantity = ($batch->received_quantity ?? 0) + $request->input('quantity');
            } else {
                $batch->received_quantity = max(0, ($batch->received_quantity ?? 0) - $request->input('quantity'));
            }
            
            $batch->save();

            $inventory = ClinicInventory::firstOrNew([
                'batch_id' => $batch->id,
                'clinic_id' => $warehouse->id,
            ]);
            
            if ($request->input('action_type') == 'add') {
                $inventory->stock_level = ($inventory->stock_level ?? 0) + $request->input('quantity');
            } else {
                $inventory->stock_level = max(0, ($inventory->stock_level ?? 0) - $request->input('quantity'));
            }
            
            $inventory->save();

            return response()->json(['message' => 'Stock updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update stock. Please try again.'], 500);
        }
    }

    /**
     * Approve transfer request
     */
    public function approveTransfer($id)
    {
        try {
            $transfer = StockTransfer::findOrFail($id);
            
            if ($transfer->status !== 'requested') {
                return response()->json(['error' => 'Transfer is not in requested status'], 400);
            }
            
            $warehouse = Clinic::where('is_warehouse', true)->first();
            
            if (!$warehouse || $transfer->source_id !== $warehouse->id) {
                return response()->json(['error' => 'Only transfers from central warehouse can be approved'], 400);
            }

            $warehouseInventory = ClinicInventory::where('batch_id', $transfer->batch_id)
                ->where('clinic_id', $warehouse->id)
                ->first();
                
            if (!$warehouseInventory || $warehouseInventory->stock_level < $transfer->quantity) {
                return response()->json(['error' => 'Insufficient stock in warehouse'], 400);
            }
            
            $warehouseInventory->stock_level -= $transfer->quantity;
            $warehouseInventory->save();

            $transfer->status = 'shipped';
            $transfer->save();

            return response()->json([
                'message' => 'Transfer approved and shipped successfully',
                'transfer' => $transfer
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to approve transfer. Please try again.'], 500);
        }
    }

    /**
     * Get drug transaction history
     */
    public function getDrugHistory($id)
    {
        try {
            $drug = Drug::with('batches')->findOrFail($id);
            
            $history = collect();
            
            // Add stock receipts
            foreach ($drug->batches as $batch) {
                $history->push([
                    'date' => $batch->created_at->format('Y-m-d H:i:s'),
                    'type' => 'Received',
                    'quantity' => '+' . $batch->received_quantity,
                    'reference' => 'BATCH-' . $batch->id,
                    'user' => 'System',
                    'notes' => 'Stock received with expiry date ' . $batch->expiry_date->format('Y-m-d')
                ]);
            }
            
            // Add sales/prescriptions
            $prescriptionItems = \App\Models\PrescriptionItem::where('drug_id', $id)
                ->with(['prescription.patient', 'prescription.doctor'])
                ->get();
                
            foreach ($prescriptionItems as $item) {
                $history->push([
                    'date' => $item->created_at->format('Y-m-d H:i:s'),
                    'type' => 'Sold',
                    'quantity' => '-' . $item->quantity,
                    'reference' => 'RX-' . $item->prescription_id,
                    'user' => $item->prescription->patient->name ?? 'Unknown Patient',
                    'notes' => 'Prescribed by Dr. ' . ($item->prescription->doctor->name ?? 'Unknown Doctor')
                ]);
            }
            
            $history = $history->sortByDesc('date')->values(); // Reset keys after sort
            
            return response()->json(['history' => $history]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load drug history.'], 500);
        }
    }
}