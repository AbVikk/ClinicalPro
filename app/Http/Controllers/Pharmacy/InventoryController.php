<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\InventoryPrediction;
use App\Models\Drug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventory predictions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all predictions with related drug information and historical usage
        $predictions = InventoryPrediction::select(
            'inventory_predictions.*',
            DB::raw('(SELECT COALESCE(SUM(received_quantity), 0) FROM drug_batches WHERE drug_name = inventory_predictions.drug_name) as current_stock'),
            DB::raw('(SELECT COUNT(*) FROM prescriptions p JOIN prescription_items pi ON p.id = pi.prescription_id WHERE pi.medication_name = inventory_predictions.drug_name AND p.created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)) as historical_usage')
        )
        ->orderBy('drug_name')
        ->orderBy('year')
        ->orderByRaw("FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
        ->get();
        
        // Determine which view to use based on user role
        switch ($user->role) {
            case 'primary_pharmacist':
                return view('pharmacy.primary_pharmacist.inventory.predictions', compact('predictions'));
            case 'senior_pharmacist':
                return view('pharmacy.senior_pharmacist.inventory.predictions', compact('predictions'));
            case 'clinic_pharmacist':
                return view('pharmacy.clinic_pharmacist.inventory.predictions', compact('predictions'));
            default:
                return view('pharmacy.primary_pharmacist.inventory.predictions', compact('predictions'));
        }
    }
}