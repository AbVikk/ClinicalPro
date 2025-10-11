<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\PrescriptionTemplate;
use App\Models\User;
use App\Models\Drug;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of prescriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Prescription::with(['patient', 'doctor', 'items']);
        
        // Filter by patient if specified
        if ($request->has('patient_id')) {
            $query->where('patient_id', $request->input('patient_id'));
        }
        
        $prescriptions = $query->orderBy('created_at', 'desc')->get();
        return view('admin.prescriptions.index', compact('prescriptions'));
    }

    /**
     * Display the specified prescription.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $prescription = Prescription::with(['patient', 'doctor', 'items.drug'])->findOrFail($id);
        
        // Get refill history from pharmacy orders
        $pharmacyOrders = $prescription->pharmacyOrder()->with(['patient', 'clinic'])->get();
        
        // Transform pharmacy orders into refill history format
        $refillHistory = [];
        foreach ($pharmacyOrders as $order) {
            $refillHistory[] = [
                'date' => $order->created_at->format('Y-m-d H:i:s'),
                'pharmacy' => $order->clinic->name ?? 'Unknown Pharmacy',
                'dispensed_by' => $order->patient->name ?? 'Unknown Staff'
            ];
        }
        
        // If no pharmacy orders exist, provide sample data for demonstration
        if (empty($refillHistory)) {
            // In a real application, this would be removed
            // For now, we'll use sample data to show the feature working
            $refillHistory = [
                // Uncomment the following lines to show sample data
                /*
                [
                    'date' => '2025-10-01 10:30:00',
                    'pharmacy' => 'Main Street Pharmacy',
                    'dispensed_by' => 'Dr. Smith'
                ],
                [
                    'date' => '2025-10-05 14:15:00',
                    'pharmacy' => 'Downtown Pharmacy',
                    'dispensed_by' => 'Dr. Johnson'
                ]
                */
            ];
        }
        
        return view('admin.prescriptions.show', compact('prescription', 'refillHistory'));
    }

    /**
     * Show the form for creating a new prescription.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $patients = User::where('role', 'patient')->get();
        $doctors = User::where('role', 'doctor')->get();
        $drugs = Drug::all();
        
        // Log the data for debugging
        Log::info('Prescription creation form data:', [
            'patients_count' => $patients->count(),
            'doctors_count' => $doctors->count(),
            'drugs_count' => $drugs->count(),
            'drugs_sample' => $drugs->take(5)->pluck('id', 'name')->toArray()
        ]);
        
        return view('admin.prescriptions.create', compact('patients', 'doctors', 'drugs'));
    }

    /**
     * Search patients by name or ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchPatients(Request $request)
    {
        $searchTerm = $request->input('search');
        
        if (empty($searchTerm)) {
            return response()->json(['patients' => []]);
        }
        
        // Search patients by name or user_id
        $patients = User::where('role', 'patient')
            ->where(function($query) use ($searchTerm) {
                $query->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('user_id', 'LIKE', "%{$searchTerm}%");
            })
            ->select('id', 'name', 'user_id')
            ->get();
        
        return response()->json(['patients' => $patients]);
    }

    /**
     * Store a newly created prescription in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Check if this is an AJAX request
            $isAjax = $request->ajax();
            
            // Log the incoming request data for debugging
            Log::info('Prescription creation request data:', [
                'is_ajax' => $isAjax,
                'all_data' => $request->all(),
                'user_id' => Auth::id(),
                'user_role' => Auth::user()->role ?? 'unknown'
            ]);
            
            // Validate the request
            $validatedData = $request->validate([
                'patient_id' => 'required|exists:users,id',
                'prescription_type' => 'required|string',
                'diagnosis' => 'required|string',
                'pharmacist_notes' => 'nullable|string',
                'save_as_template' => 'boolean',
                'template_name' => 'nullable|string',
                'medications' => 'required|array|min:1',
                'medications.*.name' => 'required|exists:drugs,id',
                'medications.*.dosage' => 'required|string',
                'medications.*.route' => 'required|string',
                'medications.*.frequency' => 'required|string',
                'medications.*.duration' => 'required|string',
                'medications.*.instructions' => 'nullable|string',
                'medications.*.allow_refills' => 'boolean',
                'medications.*.refills' => 'nullable|integer|min:0',
            ]);

            // Log the validated data
            Log::info('Validated prescription data:', $validatedData);

            // Filter out empty medication entries properly
            $medications = array_filter($validatedData['medications'], function($medication) {
                return !empty($medication['name']) && !empty($medication['dosage']);
            });

            // Check if we have at least one valid medication
            if (empty($medications)) {
                $errorMessage = 'At least one valid medication is required.';
                Log::error('Prescription creation error: ' . $errorMessage);
                
                if ($isAjax) {
                    return response()->json(['success' => false, 'error' => $errorMessage], 422);
                } else {
                    return redirect()->back()->with('error', $errorMessage)->withInput();
                }
            }

            // Verify patient exists and is a patient
            $patient = User::where('id', $validatedData['patient_id'])
                          ->where('role', 'patient')
                          ->first();
            
            if (!$patient) {
                $errorMessage = 'Invalid patient selected.';
                Log::error('Prescription creation error: ' . $errorMessage, [
                    'patient_id' => $validatedData['patient_id'],
                    'user_id' => Auth::id()
                ]);
                
                if ($isAjax) {
                    return response()->json(['success' => false, 'error' => $errorMessage], 422);
                } else {
                    return redirect()->back()->with('error', $errorMessage)->withInput();
                }
            }

            // Create the prescription
            $prescription = new Prescription();
            $prescription->patient_id = $validatedData['patient_id'];
            $prescription->doctor_id = Auth::id(); // Assuming the logged-in user is the doctor
            $prescription->status = 'active';
            $prescription->notes = $validatedData['pharmacist_notes'] ?? null;
            $prescription->save();

            // Log the created prescription
            Log::info('Prescription created with ID:', ['id' => $prescription->id]);

            // Create prescription items
            $refillsAllowed = 0;
            foreach ($medications as $index => $medication) {
                // Skip medications without a name (drug_id)
                if (empty($medication['name'])) {
                    continue;
                }
                
                $prescriptionItem = new PrescriptionItem();
                $prescriptionItem->prescription_id = $prescription->id;
                $prescriptionItem->drug_id = $medication['name'];
                $prescriptionItem->quantity = 1; // Default quantity
                $prescriptionItem->dosage_instructions = json_encode([
                    'dosage' => $medication['dosage'],
                    'route' => $medication['route'],
                    'frequency' => $medication['frequency'],
                    'duration' => $medication['duration'],
                    'instructions' => $medication['instructions'] ?? '',
                    'allow_refills' => $medication['allow_refills'] ?? false,
                    'refills' => $medication['refills'] ?? 0,
                ]);
                $prescriptionItem->fulfillment_status = 'pending';
                $prescriptionItem->save();
                
                // Count refills allowed
                if (!empty($medication['allow_refills']) && $medication['allow_refills']) {
                    $refillsAllowed += ($medication['refills'] ?? 0);
                }
                
                Log::info('Prescription item created:', [
                    'item_id' => $prescriptionItem->id, 
                    'drug_id' => $medication['name'],
                    'prescription_id' => $prescription->id
                ]);
            }

            // Update prescription with refills count
            $prescription->refills_allowed = $refillsAllowed;
            $prescription->save();

            // If save as template is checked, save the template
            if (isset($validatedData['save_as_template']) && $validatedData['save_as_template'] && isset($validatedData['template_name'])) {
                $template = new PrescriptionTemplate();
                $template->name = $validatedData['template_name'];
                $template->diagnosis = $validatedData['diagnosis'];
                $template->notes = $validatedData['pharmacist_notes'] ?? null;
                $template->created_by = Auth::id();
                
                // Prepare medications data for template
                $medicationsData = [];
                foreach ($medications as $medication) {
                    // Skip medications without a name (drug_id)
                    if (empty($medication['name'])) {
                        continue;
                    }
                    
                    $medicationsData[] = [
                        'drug_id' => $medication['name'],
                        'dosage' => $medication['dosage'],
                        'route' => $medication['route'],
                        'frequency' => $medication['frequency'],
                        'duration' => $medication['duration'],
                        'instructions' => $medication['instructions'] ?? '',
                        'allow_refills' => $medication['allow_refills'] ?? false,
                        'refills' => $medication['refills'] ?? 0,
                    ];
                }
                $template->medications = $medicationsData;
                $template->save();
                
                Log::info('Prescription template created:', ['template_id' => $template->id]);
            }

            $successMessage = 'Prescription created successfully.';
            
            // Return appropriate response based on request type
            if ($isAjax) {
                return response()->json(['success' => true, 'message' => $successMessage]);
            } else {
                return redirect()->route('admin.prescriptions.index')->with('success', $successMessage);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            $errors = $e->errors();
            Log::error('Prescription validation errors:', $errors);
            
            if ($isAjax) {
                return response()->json(['success' => false, 'errors' => $errors], 422);
            } else {
                return redirect()->back()->withErrors($errors)->withInput();
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error creating prescription: ' . $e->getMessage();
            Log::error('Error creating prescription:', [
                'error' => $e->getMessage(), 
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            // Return appropriate response based on request type
            if ($isAjax) {
                return response()->json(['success' => false, 'error' => $errorMessage], 500);
            } else {
                return redirect()->back()->with('error', $errorMessage)->withInput();
            }
        }
    }

    /**
     * Show the form for editing the specified prescription.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $prescription = Prescription::with(['patient', 'doctor', 'items.drug'])->findOrFail($id);
        $drugs = Drug::all();
        
        return view('admin.prescriptions.edit', compact('prescription', 'drugs'));
    }

    /**
     * Update the specified prescription in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $prescription = Prescription::findOrFail($id);
            
            // Log the incoming request data for debugging
            Log::info('Prescription update request data:', [
                'prescription_id' => $id,
                'all_data' => $request->all(),
                'user_id' => Auth::id()
            ]);
            
            $validatedData = $request->validate([
                'doctor_id' => 'required|exists:users,id',
                'status' => 'required|in:active,expired,filled,cancelled',
                'notes' => 'nullable|string',
                'refills_allowed' => 'required|integer|min:0',
                'medications' => 'required|array',
                'medications.*.id' => 'nullable', // Allow null or any value
                'medications.*.drug_id' => 'required|exists:drugs,id',
                'medications.*.dosage' => 'required|string',
                'medications.*.frequency' => 'required|string',
                'medications.*.duration' => 'required|string',
                'medications.*.instructions' => 'nullable|string',
            ]);
            
            // Log the validated data
            Log::info('Validated prescription update data:', $validatedData);

            // Update prescription
            $prescription->doctor_id = $validatedData['doctor_id'];
            $prescription->status = $validatedData['status'];
            $prescription->notes = $validatedData['notes'];
            $prescription->refills_allowed = $validatedData['refills_allowed'];
            $prescription->save();

            // Log before updating items
            Log::info('Prescription items before update:', [
                'prescription_id' => $prescription->id,
                'item_count' => $prescription->items->count(),
                'items' => $prescription->items->pluck('id')->toArray()
            ]);

            // Update or create prescription items
            $existingItemIds = [];
            foreach ($validatedData['medications'] as $index => $medicationData) {
                // Skip medications without a drug_id
                if (empty($medicationData['drug_id'])) {
                    Log::info('Skipping medication with empty drug_id at index ' . $index, $medicationData);
                    continue;
                }
                
                Log::info('Processing medication data at index ' . $index . ':', $medicationData);
                
                // Check if this is an existing item (has ID and ID is not empty and is numeric)
                if (!empty($medicationData['id']) && is_numeric($medicationData['id'])) {
                    // Validate that the item belongs to this prescription
                    $item = PrescriptionItem::find($medicationData['id']);
                    if ($item && $item->prescription_id == $prescription->id) {
                        Log::info('Updating existing item with ID: ' . $medicationData['id']);
                        $existingItemIds[] = $item->id;
                    } else {
                        // If item doesn't exist or doesn't belong to this prescription, create new
                        Log::info('Item ID ' . $medicationData['id'] . ' not found or doesn\'t belong to prescription, creating new item');
                        $item = new PrescriptionItem();
                        $item->prescription_id = $prescription->id;
                    }
                } else {
                    // Create new item
                    Log::info('Creating new item for prescription ID: ' . $prescription->id);
                    $item = new PrescriptionItem();
                    $item->prescription_id = $prescription->id;
                }
                
                $item->drug_id = $medicationData['drug_id'];
                $item->quantity = 1;
                $item->dosage_instructions = json_encode([
                    'dosage' => $medicationData['dosage'],
                    'frequency' => $medicationData['frequency'],
                    'duration' => $medicationData['duration'],
                    'instructions' => $medicationData['instructions'] ?? '',
                ]);
                $item->fulfillment_status = 'pending';
                $item->save();
                
                Log::info('Saved item with ID: ' . $item->id);
            }

            // Log existing item IDs
            Log::info('Existing item IDs to keep:', $existingItemIds);

            // Delete items that were removed
            if (!empty($existingItemIds)) {
                $prescription->items()->whereNotIn('id', $existingItemIds)->delete();
                Log::info('Deleted items not in list for prescription ID: ' . $prescription->id);
            } else {
                // If no existing items, delete all
                $prescription->items()->delete();
                Log::info('Deleted all items for prescription ID: ' . $prescription->id);
            }

            // Log after updating items
            $prescription->refresh();
            Log::info('Prescription items after update:', [
                'prescription_id' => $prescription->id,
                'item_count' => $prescription->items->count(),
                'items' => $prescription->items->pluck('id')->toArray()
            ]);

            return redirect()->route('admin.prescriptions.show', $prescription->id)->with('success', 'Prescription updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Prescription update validation errors:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating prescription:', [
                'error' => $e->getMessage(), 
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            return redirect()->back()->with('error', 'Error updating prescription: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for renewing the specified prescription.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function renew($id)
    {
        $prescription = Prescription::with(['patient', 'doctor', 'items.drug'])->findOrFail($id);
        
        return view('admin.prescriptions.renew', compact('prescription'));
    }

    /**
     * Store a renewed prescription in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeRenewal(Request $request, $id)
    {
        $originalPrescription = Prescription::with(['items'])->findOrFail($id);
        
        $validatedData = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'refills_allowed' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        // Create a new prescription based on the original
        $newPrescription = new Prescription();
        $newPrescription->patient_id = $originalPrescription->patient_id;
        $newPrescription->doctor_id = $validatedData['doctor_id'];
        $newPrescription->status = 'active';
        $newPrescription->notes = $validatedData['notes'];
        $newPrescription->refills_allowed = $validatedData['refills_allowed'];
        $newPrescription->save();

        // Copy all items from the original prescription
        foreach ($originalPrescription->items as $item) {
            $newItem = new PrescriptionItem();
            $newItem->prescription_id = $newPrescription->id;
            $newItem->drug_id = $item->drug_id;
            $newItem->quantity = $item->quantity;
            $newItem->dosage_instructions = $item->dosage_instructions;
            $newItem->fulfillment_status = 'pending';
            $newItem->save();
        }

        return redirect()->route('admin.prescriptions.show', $newPrescription->id)->with('success', 'Prescription renewed successfully.');
    }

    /**
     * Get patient details for AJAX request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getPatientDetails(Request $request)
    {
        $patientId = $request->input('patient_id');
        $patient = User::with('patient')->find($patientId);
        
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }
        
        // In a real application, these would come from the patient's medical records
        // For now, we'll use placeholder data, but in a real app this would be fetched from the database
        $allergies = ['Penicillin', 'Peanuts', 'Shellfish']; // This would come from patient medical history
        $conditions = ['Hypertension', 'Type 2 Diabetes', 'Asthma']; // This would come from patient medical history
        
        // Get recent prescriptions for this patient with related data
        $recentPrescriptions = Prescription::with(['items.drug', 'doctor'])
            ->where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        return response()->json([
            'patient' => $patient,
            'allergies' => $allergies,
            'conditions' => $conditions,
            'recent_prescriptions' => $recentPrescriptions
        ]);
    }

    /**
     * Get prescription template details for AJAX request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getTemplateDetails($id)
    {
        try {
            $template = PrescriptionTemplate::with('creator')->findOrFail($id);
            
            // Enhance medications with drug names
            if (is_array($template->medications)) {
                foreach ($template->medications as &$medication) {
                    if (isset($medication['drug_id'])) {
                        $drug = Drug::find($medication['drug_id']);
                        if ($drug) {
                            $medication['drug_name'] = $drug->name;
                        }
                    }
                }
            }
            
            // Get usage statistics
            $usageStats = [
                'total_uses' => $template->usage_count,
                'last_used' => $template->updated_at->format('Y-m-d'),
                'created_on' => $template->created_at->format('Y-m-d')
            ];
            
            return response()->json([
                'success' => true,
                'template' => $template,
                'usage_stats' => $usageStats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Template not found'
            ], 404);
        }
    }

    /**
     * Increment template usage count.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function useTemplate($id)
    {
        try {
            $template = PrescriptionTemplate::findOrFail($id);
            
            // Increment usage count
            $template->increment('usage_count');
            
            // Refresh the template to get updated count
            $template->refresh();
            
            // Enhance medications with drug names
            if (is_array($template->medications)) {
                foreach ($template->medications as &$medication) {
                    if (isset($medication['drug_id'])) {
                        $drug = Drug::find($medication['drug_id']);
                        if ($drug) {
                            $medication['drug_name'] = $drug->name;
                        }
                    }
                }
            }
            
            // Get usage statistics
            $usageStats = [
                'total_uses' => $template->usage_count,
                'last_used' => $template->updated_at->format('Y-m-d'),
                'created_on' => $template->created_at->format('Y-m-d')
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Template usage count updated',
                'template' => $template,
                'usage_stats' => $usageStats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Template not found'
            ], 404);
        }
    }

    /**
     * Display prescription templates.
     *
     * @return \Illuminate\Http\Response
     */
    public function templates(Request $request)
    {
        try {
            // Log that we're accessing this method
            \Illuminate\Support\Facades\Log::info('Accessing templates method');
            
            // Get the currently authenticated user
            $userId = Auth::id();
            \Illuminate\Support\Facades\Log::info('User ID: ' . $userId);
            
            // If no user is authenticated, use a default user
            if (!$userId) {
                $userId = 1;
                \Illuminate\Support\Facades\Log::info('No authenticated user, using default user ID: ' . $userId);
            }
            
            // Fetch all prescription templates with creator information
            $allTemplates = PrescriptionTemplate::with('creator')->get();
            \Illuminate\Support\Facades\Log::info('All templates count: ' . $allTemplates->count());
            
            // Fetch recently used templates (last 30 days)
            $recentlyUsed = PrescriptionTemplate::with('creator')
                ->where('updated_at', '>=', now()->subDays(30))
                ->get();
            \Illuminate\Support\Facades\Log::info('Recently used count: ' . $recentlyUsed->count());
            
            // Fetch templates created by the current user
            $myTemplates = PrescriptionTemplate::with('creator')
                ->where('created_by', $userId)
                ->get();
            \Illuminate\Support\Facades\Log::info('My templates count: ' . $myTemplates->count());
            
            // Fetch categories for the dropdown
            $categories = \App\Models\Category::all();
            
            // Fetch drugs for medication selection
            $drugs = \App\Models\Drug::all();
            
            // Fetch MG values for dosage selection
            $mgValues = \App\Models\DrugMg::all();
            
            // If this is an AJAX request for search, return JSON
            if ($request->ajax() && $request->has('search')) {
                $searchTerm = $request->input('search');
                $filteredTemplates = $allTemplates->filter(function ($template) use ($searchTerm) {
                    return stripos($template->name, $searchTerm) !== false || 
                           stripos($template->diagnosis, $searchTerm) !== false;
                });
                
                return response()->json([
                    'success' => true,
                    'templates' => $filteredTemplates
                ]);
            }
            
            // Get a sample template for details view (in a real app, this would be based on selection)
            $sampleTemplate = PrescriptionTemplate::with('creator')->first();
            \Illuminate\Support\Facades\Log::info('Sample template: ' . ($sampleTemplate ? 'found' : 'not found'));
            
            // If we have a sample template, enhance it with drug names
            if ($sampleTemplate && is_array($sampleTemplate->medications)) {
                // Create a new array to avoid modifying the original
                $medications = $sampleTemplate->medications;
                foreach ($medications as &$medication) {
                    if (isset($medication['drug_id'])) {
                        $drug = Drug::find($medication['drug_id']);
                        if ($drug) {
                            $medication['drug_name'] = $drug->name;
                        }
                    }
                }
                // Assign the modified array back to the template
                $sampleTemplate->medications = $medications;
            }
            
            \Illuminate\Support\Facades\Log::info('Returning view');
            return view('admin.prescriptions.templates', compact('allTemplates', 'recentlyUsed', 'myTemplates', 'sampleTemplate', 'categories', 'drugs', 'mgValues'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in templates method: ' . $e->getMessage());
            // Instead of returning a 404 view, let's return a simple error message for debugging
            return response()->json(['error' => 'Error in templates method: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Display prescription template details page.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function viewTemplate($id)
    {
        try {
            \Illuminate\Support\Facades\Log::info('Viewing template with ID: ' . $id);
            $template = PrescriptionTemplate::with('creator')->findOrFail($id);
            \Illuminate\Support\Facades\Log::info('Template found: ' . $template->name);
            
            // Enhance medications with drug names
            if (is_array($template->medications)) {
                foreach ($template->medications as &$medication) {
                    if (isset($medication['drug_id'])) {
                        $drug = Drug::find($medication['drug_id']);
                        if ($drug) {
                            $medication['drug_name'] = $drug->name;
                        }
                    }
                }
            }
            
            // Get usage statistics
            $usageStats = [
                'total_uses' => $template->usage_count,
                'last_used' => $template->updated_at->format('Y-m-d'),
                'created_on' => $template->created_at->format('Y-m-d')
            ];
            
            \Illuminate\Support\Facades\Log::info('Returning view for template: ' . $template->name);
            return view('admin.prescriptions.template-view', compact('template', 'usageStats'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error viewing template: ' . $e->getMessage());
            return redirect()->route('admin.prescriptions.templates')->with('error', 'Template not found');
        }
    }
    
    /**
     * Display prescription template edit page.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editTemplate($id)
    {
        try {
            \Illuminate\Support\Facades\Log::info('Editing template with ID: ' . $id);
            $template = PrescriptionTemplate::with('creator')->findOrFail($id);
            \Illuminate\Support\Facades\Log::info('Template found for editing: ' . $template->name);
            
            // Fetch categories for the dropdown
            $categories = \App\Models\Category::all();
            
            // Fetch drugs for medication selection
            $drugs = \App\Models\Drug::all();
            
            // Fetch MG values for dosage selection
            $mgValues = \App\Models\DrugMg::all();
            
            \Illuminate\Support\Facades\Log::info('Returning edit view for template: ' . $template->name);
            return view('admin.prescriptions.template-edit', compact('template', 'categories', 'drugs', 'mgValues'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error editing template: ' . $e->getMessage());
            return redirect()->route('admin.prescriptions.templates')->with('error', 'Template not found');
        }
    }
    
    /**
     * Update prescription template.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateTemplate(Request $request, $id)
    {
        try {
            \Illuminate\Support\Facades\Log::info('Updating template with ID: ' . $id);
            \Illuminate\Support\Facades\Log::info('Request data: ', $request->all());
            
            $template = PrescriptionTemplate::findOrFail($id);
            \Illuminate\Support\Facades\Log::info('Template found for updating: ' . $template->name);
            
            // Validate the request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'nullable|exists:categories,id',
                'description' => 'nullable|string',
                'medications' => 'required|array|min:1',
                'medications.*.name' => 'required|string|max:255',
                'medications.*.drug_id' => 'nullable|exists:drugs,id',
                'medications.*.dosage' => 'required|string|max:255',
                'medications.*.route' => 'required|string|max:255',
                'medications.*.frequency' => 'nullable|string|max:255',
                'medications.*.duration' => 'nullable|string|max:255',
                'medications.*.instructions' => 'nullable|string|max:255',
            ]);
            
            \Illuminate\Support\Facades\Log::info('Validation passed, updating template: ' . $template->name);
            
            // Update the template
            $template->name = $validatedData['name'];
            $template->diagnosis = $validatedData['category'] ?? null;
            $template->notes = $validatedData['description'] ?? null;
            
            // Format medications array properly
            $medications = [];
            foreach ($validatedData['medications'] as $medication) {
                $medications[] = [
                    'name' => $medication['name'],
                    'drug_id' => $medication['drug_id'] ?? null,
                    'dosage' => $medication['dosage'],
                    'route' => $medication['route'],
                    'frequency' => $medication['frequency'] ?? null,
                    'duration' => $medication['duration'] ?? null,
                    'instructions' => $medication['instructions'] ?? null,
                ];
            }
            
            $template->medications = $medications;
            $template->save();
            
            \Illuminate\Support\Facades\Log::info('Template updated successfully: ' . $template->name);
            
            return redirect()->route('admin.prescriptions.template.view', $template->id)->with('success', 'Template updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validation error: ', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updating template: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating template: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Store a newly created prescription template in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeTemplate(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Log::info('Store template request data:', $request->all());
            
            // Validate the request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'nullable|exists:categories,id',
                'description' => 'nullable|string',
                'medications' => 'required|array|min:1',
                'medications.*.name' => 'required|string|max:255',
                'medications.*.drug_id' => 'nullable|exists:drugs,id',
                'medications.*.dosage' => 'required|string|max:255',
                'medications.*.route' => 'required|string|max:255',
                'medications.*.frequency' => 'nullable|string|max:255',
                'medications.*.instructions' => 'nullable|string|max:255',
            ]);
            
            \Illuminate\Support\Facades\Log::info('Validated data:', $validatedData);
            
            // Create the template
            $template = new PrescriptionTemplate();
            $template->name = $validatedData['name'];
            $template->diagnosis = $validatedData['category'] ?? null;
            $template->notes = $validatedData['description'] ?? null;
            $template->created_by = Auth::id();
            
            // Format medications array properly
            $medications = [];
            foreach ($validatedData['medications'] as $medication) {
                $medications[] = [
                    'name' => $medication['name'],
                    'drug_id' => $medication['drug_id'] ?? null,
                    'dosage' => $medication['dosage'],
                    'route' => $medication['route'],
                    'frequency' => $medication['frequency'] ?? null,
                    'instructions' => $medication['instructions'] ?? null,
                ];
            }
            
            $template->medications = $medications;
            $template->usage_count = 0;
            $template->save();
            
            \Illuminate\Support\Facades\Log::info('Template saved successfully with ID: ' . $template->id);
            
            return response()->json(['success' => true, 'message' => 'Template created successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Illuminate\Support\Facades\Log::error('Validation error:', $e->errors());
            return response()->json(['success' => false, 'error' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error saving template: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Show the form for using a prescription template.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function useTemplateForm($id)
    {
        try {
            \Illuminate\Support\Facades\Log::info('Using template with ID: ' . $id);
            $template = PrescriptionTemplate::with('creator')->findOrFail($id);
            \Illuminate\Support\Facades\Log::info('Template found for use: ' . $template->name);
            
            // Fetch patients for the dropdown
            $patients = User::where('role', 'patient')->get();
            
            // Fetch doctors for the dropdown
            $doctors = User::where('role', 'doctor')->get();
            
            // Fetch drugs for medication selection
            $drugs = Drug::all();
            
            \Illuminate\Support\Facades\Log::info('Returning use template view for template: ' . $template->name);
            return view('admin.prescriptions.use-template', compact('template', 'patients', 'doctors', 'drugs'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error using template: ' . $e->getMessage());
            return redirect()->route('admin.prescriptions.templates')->with('error', 'Template not found');
        }
    }
    
    /**
     * Print the specified prescription.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $prescription = Prescription::with(['patient', 'doctor', 'items.drug'])->findOrFail($id);
        
        return view('admin.prescriptions.print', compact('prescription'));
    }

    /**
     * Cancel the specified prescription.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $prescription = Prescription::findOrFail($id);
        
        // Update the prescription status to cancelled
        $prescription->status = 'cancelled';
        $prescription->save();

        return redirect()->route('admin.prescriptions.index')->with('success', 'Prescription cancelled successfully.');
    }
}