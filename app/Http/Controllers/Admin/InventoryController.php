<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryPrediction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventory predictions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get the latest predictions grouped by drug
        $predictions = InventoryPrediction::select(
            'drug_name',
            DB::raw('MAX(generated_at) as latest_generated_at')
        )
        ->groupBy('drug_name')
        ->pluck('latest_generated_at', 'drug_name');
        
        // Get all predictions for the latest generation
        $latestPredictions = InventoryPrediction::whereIn('drug_name', $predictions->keys())
            ->whereIn('generated_at', $predictions->values())
            ->orderBy('drug_name')
            ->orderBy('year')
            ->orderByRaw("FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
            ->get();
        
        // Group predictions by drug
        $groupedPredictions = $latestPredictions->groupBy('drug_name');
        
        return view('admin.inventory.predictions', compact('groupedPredictions'));
    }
}