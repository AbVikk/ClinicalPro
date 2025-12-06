<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan; // <--- THIS FIXED THE ERROR
use App\Services\PharmacyService;
use App\Models\Drug;
use App\Models\InventoryPrediction;

class PharmacyDashboardController extends Controller
{
    protected $pharmacyService;

    public function __construct(PharmacyService $pharmacyService)
    {
        $this->pharmacyService = $pharmacyService;
    }

    public function index()
    {
        $user = Auth::user();
        
        // 1. Get Metrics via Service (Handles Global vs Local logic)
        $metrics = $this->pharmacyService->getDashboardMetrics($user);

        // 2. Get Common Data (Predictions, Recent Inventory)
        $predictions = InventoryPrediction::latest()->limit(5)->get();
        
        // For the inventory table, we might want different queries based on role,
        // but the Service could handle "getRecentInventory($user)" too. 
        // For now, let's do a simple fetch here:
        $inventory = Drug::with('batches')->limit(10)->get();

        // 3. Combine Data
        $data = array_merge($metrics, [
            'user' => $user,
            'predictions' => $predictions,
            'inventory' => $inventory,
            // Add any missing keys required by the view to prevent "Undefined variable"
            'totalProfit' => $metrics['totalSalesAmount'] * 0.2, // Estimating 20% margin if COGS logic is missing
            'topProducts' => collect([]), // Placeholder collection
            'outOfStockItems' => 0 // Placeholder
        ]);

        // 4. Determine View based on Role
        // We will map all roles to use the 'primary_pharmacist.dashboard' view 
        // because it is the most complete one (from Batch 7), and the data passed
        // will automatically adjust (e.g., Local stock vs Global stock).
        $viewName = 'pharmacy.primary_pharmacist.dashboard';

        return view($viewName, $data);
    }

    /**
     * Trigger the AI Analysis manually via AJAX
     */
    public function runAiAnalysis() {
        // This runs the console command 'php artisan inventory:predict'
        // Note: In production, this might take 5-10 seconds.
        Artisan::call('inventory:predict');
        
        return response()->json(['success' => true, 'message' => 'AI Analysis completed successfully.']);
    }
}