<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Prescription;
use App\Models\User;
use App\Services\PharmacyService;

class PharmacySalesController extends Controller
{
    protected $pharmacyService;

    public function __construct(PharmacyService $pharmacyService)
    {
        $this->pharmacyService = $pharmacyService;
    }

    /**
     * Show the main POS dashboard.
     * We will standardize on one view file in Phase 4.
     */
    public function index()
    {
        // Standardize on the robust 'pos' view we saw in Batch 7
        return view('pharmacy.pos'); 
    }

    /**
     * AJAX: Live Search for Patients & Prescriptions
     */
    public function searchPatient(Request $request)
    {
        $query = trim($request->get('q'));
        if (strlen($query) < 2) return response()->json([]);

        try {
            $user = Auth::user();
            
            $patients = User::where('role', 'patient')
            ->where('hospital_id', $user->hospital_id) 
                ->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('email', 'LIKE', "%{$query}%")
                      ->orWhere('phone', 'LIKE', "%{$query}%")
                      ->orWhere('user_id', 'LIKE', "%{$query}%");
                });

            // Filter prescriptions based on role
            $patients = $patients->with(['prescriptions' => function($q) use ($user) {
                $q->whereIn('status', ['dispense_ready', 'active'])
                  ->orderBy('created_at', 'desc')
                  ->with(['items.drug', 'doctor']);

                // Clinic Pharmacists only see scripts relevant to their clinic (via existing order or inventory)
                if ($user->role !== 'primary_pharmacist' && $user->clinic_id) {
                    // Logic: Show if it was sent to this clinic OR if no specific pharmacy is assigned yet
                    // This is open for business logic adjustment, but for now we allow broad search
                    // to facilitate Walk-Ins/New Scripts.
                }
            }])
            ->limit(5)
            ->get();

            // Format for frontend
            $patients = $patients->map(function($patient) {
                // Only return necessary fields and up to 3 recent scripts
                $patient->prescriptions = $patient->prescriptions->take(3)->map(function($p) {
                    $p->items->transform(function($item) {
                        // Parse dosage string "500mg||tablet||3 days||..."
                        $parts = explode('||', $item->dosage_instructions ?? '');
                        $item->dosage_display = implode(' ', array_filter($parts));
                        return $item;
                    });
                    return $p;
                });
                return $patient;
            });

            return response()->json($patients);

        } catch (\Exception $e) {
            Log::error("[PharmacySales] Patient search error: " . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * AJAX: Search for drugs (Delegates to Service)
     */
    public function searchDrugs(Request $request)
    {
        $query = trim($request->query('q'));
        if (strlen($query) < 1) return response()->json([]);

        try {
            $results = $this->pharmacyService->searchDrugs($query, Auth::user());
            return response()->json($results);
        } catch (\Exception $e) {
            Log::error("[PharmacySales] Drug search error: " . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * AJAX: Load a prescription into the Cart
     */
    public function loadPrescription(Prescription $prescription)
    {
        try {
            $prescription->load('items.drug');
            $user = Auth::user();

            // Calculate stock availability for this specific pharmacist
            // We reuse the search logic or check specifically
            
            $cartItems = $prescription->items->map(function ($item) use ($user) {
                $drug = $item->drug;
                if (!$drug) return null;

                $searchResults = $this->pharmacyService->searchDrugs($drug->name, $user, 1);
                $stock = $searchResults->first()['stock'] ?? 0;

                // Parse Dosage
                $parts = explode('||', $item->dosage_instructions ?? '');
                
                return [
                    'id' => $drug->id,
                    'name' => $drug->name,
                    'quantity' => $item->quantity,
                    'price' => (float) $drug->unit_price,
                    'subtotal' => (float) ($item->quantity * $drug->unit_price),
                    'stock_check' => $stock,
                    'dosage' => $parts[0] ?? '',
                    'type' => $parts[1] ?? '',
                    'duration' => $parts[2] ?? '',
                    'instructions' => $parts[4] ?? ''
                ];
            })->filter();

            return response()->json([
                'status' => 'success',
                'patient_name' => $prescription->patient->name ?? 'Unknown',
                'items' => $cartItems->values(),
                'prescription_id' => $prescription->id,
                'total' => $cartItems->sum('subtotal'),
            ]);

        } catch (\Exception $e) {
            Log::error("[PharmacySales] Load Prescription Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to load prescription.'], 500);
        }
    }

    /**
     * Process Sale (Delegates to Service)
     */
    public function processSale(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'total' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        try {
            $result = $this->pharmacyService->processSale(Auth::user(), $request->all());

            // Determine redirect URL based on role
            $rolePrefix = match(Auth::user()->role) {
                'primary_pharmacist' => 'primary_pharmacist',
                'senior_pharmacist' => 'senior_pharmacist',
                default => 'clinic_pharmacist'
            };

            return response()->json([
                'status' => 'success',
                'message' => 'Sale processed successfully',
                'order_id' => $result['order']->id,
                'receipt_url' => route($rolePrefix . '.sales.receipt', $result['order']->id)
            ]);

        } catch (\Exception $e) {
            Log::error("[PharmacySales] Process Sale Error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Transaction failed.'], 500);
        }
    }

    /**
     * Generate Receipt
     */
    public function generateReceipt($orderId)
    {
        $order = \App\Models\PharmacyOrder::with(['items.drug', 'patient', 'pharmacist'])->findOrFail($orderId);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.pharmacy_receipt', compact('order'));
        $pdf->setPaper([0, 0, 226, 600], 'portrait'); // Thermal paper size
        
        return $pdf->stream("Receipt-{$orderId}.pdf");
    }

    /**
     * Create Walk-In Patient
     */
    public function createWalkIn(Request $request)
    {
        $request->validate(['name' => 'required|string', 'phone' => 'nullable|string']);

        // Create a lightweight user record
        $user = User::create([
            'name' => $request->name,
            'email' => 'walkin_' . uniqid() . '@clinicalpro.local',
            'phone' => $request->phone,
            'password' => bcrypt(uniqid()), // Random password
            'role' => 'patient',
            'status' => 'verified',
            'user_id' => 'W-' . strtoupper(uniqid()),
            'hospital_id' => Auth::user()->hospital_id,
        ]);

        return response()->json(['user_id' => $user->id, 'name' => $user->name]);
    }
}