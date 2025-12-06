<?php

namespace App\Http\Controllers\Pharmacy\PrimaryPharmacist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PharmacyOrder;
use App\Models\User;

class SoldOrdersController extends Controller
{
    /**
     * Display a listing of sold pharmacy orders.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Build the base query for pharmacy orders
        $baseQuery = PharmacyOrder::with(['patient', 'pharmacist', 'clinic', 'items.drug'])
            ->where('hospital_id', $user->hospital_id) // FIX: Scope to hospital
            ->where('status', 'completed') // Only show completed orders
            ->orderBy('created_at', 'desc');
        
        // Apply search filter if provided (search across all relevant fields)
        $search = $request->get('search');
        if ($search) {
            $baseQuery->where(function($query) use ($search) {
                // Search in patient information
                $query->whereHas('patient', function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('user_id', 'LIKE', "%{$search}%");
                })
                // Search in pharmacist information
                ->orWhereHas('pharmacist', function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })
                // Search in clinic information
                ->orWhereHas('clinic', function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })
                // Search in order ID
                ->orWhere('id', 'LIKE', "%{$search}%")
                // Search in drug names
                ->orWhereHas('items.drug', function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })
                // Search in total amount
                ->orWhere('total_amount', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply pharmacist filter if provided
        $pharmacistId = $request->get('pharmacist');
        if ($pharmacistId) {
            $baseQuery->where('pharmacist_id', $pharmacistId);
        }
        
        // Apply date range filters if provided
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        if ($startDate) {
            $baseQuery->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $baseQuery->whereDate('created_at', '<=', $endDate);
        }
        
        $orders = $baseQuery->paginate(10)->appends(request()->query());
        
        // Get all pharmacists for the filter dropdown
        $pharmacists = User::where('hospital_id', $user->hospital_id) // FIX: Scope to hospital
            ->where('role', 'clinic_pharmacist')
            ->orWhere('role', 'senior_pharmacist')
            ->orderBy('name')
            ->get();
        
        // Check if this is an AJAX request for live search
        if ($request->ajax()) {
            // Return only the table partial view for AJAX requests
            $tableContent = view('pharmacy.primary_pharmacist.sold_orders.partials.table', compact('orders'))->render();
            $paginationContent = view('pharmacy.primary_pharmacist.sold_orders.partials.pagination', compact('orders'))->render();
            
            return response()->json([
                'table' => $tableContent,
                'pagination' => $paginationContent
            ]);
        }
        
        return view('pharmacy.primary_pharmacist.sold_orders.index', compact('orders', 'search', 'pharmacists', 'pharmacistId', 'startDate', 'endDate'));
    }
}