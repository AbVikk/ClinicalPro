<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
     * Show the form for creating a new drug.
     */
    public function showCreateDrugForm()
    {
        try {
            $categories = DrugCategory::all();
            $mgs = DrugMg::all();
            return view('admin.pharmacy.drugs.create', compact('categories', 'mgs'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load the drug creation form. Please try again.');
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
            // Create a new DrugBatch record with unique batch_uuid
            $batch = new DrugBatch();
            $batch->batch_uuid = (string) Str::uuid();
            $batch->drug_id = $request->input('drug_id');
            $batch->supplier_id = $request->input('supplier_id');
            $batch->received_quantity = $request->input('received_quantity');
            $batch->expiry_date = $request->input('expiry_date');
            $batch->save();

            // Find the central warehouse (assuming it's the clinic with is_warehouse = true)
            $warehouse = Clinic::where('is_warehouse', true)->first();
            
            if (!$warehouse) {
                return response()->json(['error' => 'Central warehouse not found'], 404);
            }

            // Update clinic_inventories for the Central Warehouse
            $inventory = ClinicInventory::firstOrNew([
                'batch_id' => $batch->id,
                'clinic_id' => $warehouse->id,
            ]);
            
            $inventory->stock_level = $inventory->stock_level + $request->input('received_quantity');
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
     * Approve transfer request
     */
    public function approveTransfer($id)
    {
        try {
            $transfer = StockTransfer::findOrFail($id);
            
            // Check if transfer is in requested status
            if ($transfer->status !== 'requested') {
                return response()->json(['error' => 'Transfer is not in requested status'], 400);
            }
            
            // Find the central warehouse (assuming it's the clinic with is_warehouse = true)
            $warehouse = Clinic::where('is_warehouse', true)->first();
            
            if (!$warehouse || $transfer->source_id !== $warehouse->id) {
                return response()->json(['error' => 'Only transfers from central warehouse can be approved'], 400);
            }

            // Decrement stock_level in the Central Warehouse for the transferred batch
            $warehouseInventory = ClinicInventory::where('batch_id', $transfer->batch_id)
                ->where('clinic_id', $warehouse->id)
                ->first();
                
            if (!$warehouseInventory || $warehouseInventory->stock_level < $transfer->quantity) {
                return response()->json(['error' => 'Insufficient stock in warehouse'], 400);
            }
            
            $warehouseInventory->stock_level -= $transfer->quantity;
            $warehouseInventory->save();

            // Set stock_transfers.status to 'shipped'
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
     * Manage drug catalog
     */
    public function createDrug(Request $request)
    {
        try {
            $request->validate([
                // Basic Information
                'name' => 'required|string|max:255',
                'generic_name' => 'required|string|max:255',
                'category' => 'required|exists:drug_categories,name',
                'strength_mg' => 'required|exists:drug_mg,mg_value',
                'medicine_type' => 'required|string|in:OTC,Controlled',
                'description' => 'nullable|string',
                'medicine_form' => 'required|string|in:Tablet,Capsule,Syrup,Injection,Cream/Ointment,Drops,Other',
                
                // Detailed Information
                'manufacturer' => 'nullable|string|max:255',
                'supplier' => 'nullable|string|max:255',
                'manufacturing_date' => 'nullable|date',
                'expiry_date' => 'required|date|after:today',
                'batch_number' => 'nullable|string|max:255',
                'dosage' => 'nullable|string|max:255',
                'side_effects' => 'nullable|string',
                'precautions' => 'nullable|string',
                
                // Inventory & Pricing
                'initial_quantity' => 'required|integer|min:0',
                'reorder_level' => 'nullable|integer|min:0',
                'maximum_level' => 'nullable|integer|min:0',
                'purchase_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
                'tax_rate' => 'nullable|numeric|min:0|max:100',
                'storage_conditions' => 'nullable|array',
                'storage_conditions.*' => 'string|in:Room Temperature,Refrigerated,Frozen,Protect from Light',
                'is_active' => 'boolean',
                
                // Image uploads
                'medicine_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'package_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Create the drug
            $drug = new Drug();
            $drug->name = $request->input('name');
            $drug->category = $request->input('category');
            $drug->strength_mg = $request->input('strength_mg');
            $drug->unit_price = $request->input('selling_price');
            
            // Set is_controlled based on medicine_type
            $drug->is_controlled = $request->input('medicine_type') === 'Controlled';
            
            // Store additional fields in the details JSON column
            $details = [
                'generic_name' => $request->input('generic_name'),
                'medicine_type' => $request->input('medicine_type'),
                'description' => $request->input('description'),
                'medicine_form' => $request->input('medicine_form'),
                'manufacturer' => $request->input('manufacturer'),
                'supplier' => $request->input('supplier'),
                'manufacturing_date' => $request->input('manufacturing_date'),
                'batch_number' => $request->input('batch_number'),
                'dosage' => $request->input('dosage'),
                'side_effects' => $request->input('side_effects'),
                'precautions' => $request->input('precautions'),
                'reorder_level' => $request->input('reorder_level'),
                'maximum_level' => $request->input('maximum_level'),
                'purchase_price' => $request->input('purchase_price'),
                'selling_price' => $request->input('selling_price'),
                'tax_rate' => $request->input('tax_rate'),
                'storage_conditions' => $request->input('storage_conditions'),
                'is_active' => $request->input('is_active', true),
            ];
            
            // Handle image uploads if provided
            if ($request->hasFile('medicine_image')) {
                $medicineImage = $request->file('medicine_image');
                $imageName = time() . '_medicine_' . $medicineImage->getClientOriginalName();
                $medicineImage->storeAs('public/drugs', $imageName);
                $details['medicine_image'] = $imageName;
            }
            
            if ($request->hasFile('package_image')) {
                $packageImage = $request->file('package_image');
                $imageName = time() . '_package_' . $packageImage->getClientOriginalName();
                $packageImage->storeAs('public/drugs', $imageName);
                $details['package_image'] = $imageName;
            }
            
            $drug->details = json_encode($details);
            $drug->save();

            // Create initial batch for inventory
            $batch = new DrugBatch();
            $batch->batch_uuid = (string) Str::uuid();
            $batch->drug_id = $drug->id;
            $batch->received_quantity = $request->input('initial_quantity');
            $batch->expiry_date = $request->input('expiry_date');
            $batch->save();

            // Find the central warehouse
            $warehouse = Clinic::where('is_warehouse', true)->first();
            
            if ($warehouse) {
                // Update clinic_inventories for the Central Warehouse
                $inventory = ClinicInventory::firstOrNew([
                    'batch_id' => $batch->id,
                    'clinic_id' => $warehouse->id,
                ]);
                
                $inventory->stock_level = $inventory->stock_level + $request->input('initial_quantity');
                $inventory->save();
            }

            return redirect()->route('admin.pharmacy.dashboard')->with('success', 'Drug created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create drug. Please check the form and try again.');
        }
    }

    /**
     * Update drug catalog
     */
    public function updateDrug(Request $request, $id)
    {
        try {
            $drug = Drug::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'generic_name' => 'nullable|string|max:255',
                'category' => 'required|exists:drug_categories,name',
                'medicine_type' => 'required|string|in:OTC,Controlled',
                'manufacturer' => 'nullable|string|max:255',
                'unit_price' => 'required|numeric|min:0',
                'purchase_price' => 'nullable|numeric|min:0',
                'description' => 'nullable|string',
                'dosage' => 'nullable|string',
                'side_effects' => 'nullable|string',
                'storage_location' => 'nullable|string|max:255',
            ]);

            // Update basic drug information
            $drug->name = $request->input('name');
            $drug->category = $request->input('category');
            $drug->unit_price = $request->input('unit_price');
            $drug->is_controlled = $request->input('medicine_type') === 'Controlled';
            
            // Update details JSON
            $details = $drug->details ?? [];
            $details['generic_name'] = $request->input('generic_name');
            $details['medicine_type'] = $request->input('medicine_type');
            $details['manufacturer'] = $request->input('manufacturer');
            $details['purchase_price'] = $request->input('purchase_price');
            $details['description'] = $request->input('description');
            $details['dosage'] = $request->input('dosage');
            $details['side_effects'] = $request->input('side_effects');
            $details['storage_location'] = $request->input('storage_location');
            
            $drug->details = $details;
            $drug->save();

            return redirect()->route('admin.pharmacy.drugs.view', $drug->id)->with('success', 'Drug updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update drug. Please check the form and try again.');
        }
    }
    
    /**
     * Update drug stock
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

            // Find the drug
            $drug = Drug::findOrFail($request->input('drug_id'));

            // Find the central warehouse (assuming it's the clinic with is_warehouse = true)
            $warehouse = \App\Models\Clinic::where('is_warehouse', true)->first();
            
            if (!$warehouse) {
                return response()->json(['error' => 'Central warehouse not found'], 404);
            }

            // Create or update the drug batch
            $batch = \App\Models\DrugBatch::firstOrNew([
                'batch_uuid' => $request->input('batch_number'),
                'drug_id' => $drug->id,
            ]);
            
            $batch->expiry_date = $request->input('expiry_date');
            
            // Update quantity based on action type
            if ($request->input('action_type') == 'add') {
                $batch->received_quantity = ($batch->received_quantity ?? 0) + $request->input('quantity');
            } else {
                $batch->received_quantity = max(0, ($batch->received_quantity ?? 0) - $request->input('quantity'));
            }
            
            $batch->save();

            // Update clinic inventory
            $inventory = \App\Models\ClinicInventory::firstOrNew([
                'batch_id' => $batch->id,
                'clinic_id' => $warehouse->id,
            ]);
            
            // Update stock level based on action type
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
     * View drug details
     */
    public function viewDrug($id)
    {
        try {
            $drug = Drug::with('batches')->findOrFail($id);
            
            // Get prescription items for this drug (transactions)
            $prescriptionItems = \App\Models\PrescriptionItem::where('drug_id', $id)
                ->with(['prescription.patient', 'prescription.doctor'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
                
            // Get alternative drugs (same category but different)
            $alternatives = Drug::where('category', $drug->category)
                ->where('id', '!=', $id)
                ->limit(5)
                ->get();
            
            return view('admin.pharmacy.drugs.view', compact('drug', 'prescriptionItems', 'alternatives'));
        } catch (\Exception $e) {
            return redirect()->route('admin.pharmacy.dashboard')->with('error', 'Failed to load drug details.');
        }
    }
    
    /**
     * Get drug transaction history
     */
    public function getDrugHistory($id)
    {
        try {
            $drug = Drug::with('batches')->findOrFail($id);
            
            // Get all transaction history for this drug
            // This would include:
            // 1. Stock receipts (from DrugBatch)
            // 2. Sales (from PrescriptionItem)
            // 3. Transfers (from StockTransfer)
            
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
            
            // Add stock transfers
            $transfers = \App\Models\StockTransfer::whereHas('batch', function($query) use ($id) {
                $query->where('drug_id', $id);
            })->with(['sourceClinic', 'destinationClinic'])->get();
            
            foreach ($transfers as $transfer) {
                $history->push([
                    'date' => $transfer->created_at->format('Y-m-d H:i:s'),
                    'type' => 'Transferred',
                    'quantity' => '-' . $transfer->quantity,
                    'reference' => 'TRANSFER-' . $transfer->id,
                    'user' => 'Pharmacy System',
                    'notes' => 'Transfer from ' . ($transfer->sourceClinic->name ?? 'Unknown') . ' to ' . ($transfer->destinationClinic->name ?? 'Unknown')
                ]);
            }
            
            // Sort by date descending
            $history = $history->sortByDesc('date');
            
            return response()->json(['history' => $history]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load drug history.'], 500);
        }
    }
    
    /**
     * Delete a drug
     */
    public function deleteDrug($id)
    {
        try {
            $drug = Drug::findOrFail($id);
            
            // Check if the drug has any related records that would prevent deletion
            $hasPrescriptions = $drug->prescriptionItems()->exists();
            $hasBatches = $drug->batches()->exists();
            
            if ($hasPrescriptions) {
                return redirect()->back()->with('error', 'Cannot delete drug with existing prescriptions.');
            }
            
            // Delete related batches and inventory records
            if ($hasBatches) {
                foreach ($drug->batches as $batch) {
                    // Delete clinic inventory records for this batch
                    $batch->clinicInventories()->delete();
                    // Delete stock transfers for this batch
                    $batch->stockTransfers()->delete();
                    // Delete the batch itself
                    $batch->delete();
                }
            }
            
            // Delete the drug
            $drug->delete();
            
            return redirect()->back()->with('success', 'Drug deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete drug. Please try again.');
        }
    }
    
    /**
     * Edit drug details
     */
    public function editDrug($id)
    {
        try {
            $drug = Drug::findOrFail($id);
            $categories = \App\Models\DrugCategory::all();
            $mgs = \App\Models\DrugMg::all();
            return view('admin.pharmacy.drugs.edit', compact('drug', 'categories', 'mgs'));
        } catch (\Exception $e) {
            return redirect()->route('admin.pharmacy.dashboard')->with('error', 'Failed to load drug edit form.');
        }
    }
}