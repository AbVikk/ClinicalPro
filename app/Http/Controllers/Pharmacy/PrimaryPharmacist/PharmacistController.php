<?php

namespace App\Http\Controllers\Pharmacy\PrimaryPharmacist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth; // Add Auth facade

class PharmacistController extends Controller
{
    /**
     * Display a listing of pharmacists (only senior and clinic pharmacists).
     */
    public function index(Request $request)
    {
        // Only show senior and clinic pharmacists for primary pharmacist
        $validPharmacistRoles = [
            'senior_pharmacist', 
            'clinic_pharmacist'
        ];
        
        // Gets the current filter (e.g., 'senior_pharmacist' or null)
        $roleFilter = $request->get('role');
        
        // Get search term
        $search = $request->get('search');

        $pharmacists = User::whereIn('role', $validPharmacistRoles)
            ->where('hospital_id', Auth::user()->hospital_id) // FIX: Scope to hospital
            ->when($roleFilter && in_array($roleFilter, $validPharmacistRoles), function ($query) use ($roleFilter) {
                // Filters the database based on the query parameter
                return $query->where('role', $roleFilter);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20);
            
        // Set page title based on filter
        if ($roleFilter && in_array($roleFilter, $validPharmacistRoles)) {
            $pageTitle = ucwords(str_replace('_', ' ', $roleFilter)) . ' List';
        } else {
            $pageTitle = 'All Pharmacists';
        }

        // Check if request is AJAX
        if ($request->ajax()) {
            // Return only the table content for AJAX requests
            return view('pharmacy.primary_pharmacist.pharmacists.partials.table', compact('pharmacists'))->render();
        }

        return view('pharmacy.primary_pharmacist.pharmacists.index', compact('pharmacists', 'pageTitle', 'roleFilter'));
    }

    /**
     * Display the specified pharmacist.
     */
    public function show(User $pharmacist)
    {
        // Security check
        if ($pharmacist->hospital_id !== Auth::user()->hospital_id) abort(403);
        
        // Ensure the user is a pharmacist (senior or clinic only)
        $validPharmacistRoles = [
            'senior_pharmacist', 
            'clinic_pharmacist'
        ];
        
        if (!in_array($pharmacist->role, $validPharmacistRoles)) {
            abort(404);
        }

        return view('pharmacy.primary_pharmacist.pharmacists.show', compact('pharmacist'));
    }

    /**
     * Update the specified pharmacist status.
     */
    public function update(Request $request, User $pharmacist)
    {
        // Security check
        if ($pharmacist->hospital_id !== Auth::user()->hospital_id) abort(403);
        
        // Ensure the user is a pharmacist (senior or clinic only)
        $validPharmacistRoles = [
            'senior_pharmacist', 
            'clinic_pharmacist'
        ];
        
        if (!in_array($pharmacist->role, $validPharmacistRoles)) {
            abort(404);
        }

        // Validate the request
        $request->validate([
            'status' => 'required|in:active,suspended,pending',
        ]);

        // Update the pharmacist status
        $pharmacist->update([
            'status' => $request->status,
        ]);

        // Note: We don't need a whistleblower here because changing status
        // doesn't affect the *total count* of users on the dashboard.

        return redirect()->back()->with('success', 'Pharmacist status updated successfully.');
    }

    /**
     * Remove the specified pharmacist from storage.
     */
    public function destroy(User $pharmacist)
    {
        // Security check
        if ($pharmacist->hospital_id !== Auth::user()->hospital_id) abort(403);
        
        // Ensure the user is a pharmacist (senior or clinic only)
        $validPharmacistRoles = [
            'senior_pharmacist', 
            'clinic_pharmacist'
        ];
        
        if (!in_array($pharmacist->role, $validPharmacistRoles)) {
            abort(404);
        }

        // Delete the pharmacist
        $pharmacist->delete();

        // --- THIS IS THE "WHISTLEBLOWER" ---
        // A user (a pharmacist) was deleted. Erase all user-related "whiteboard" answers!
        Cache::forget("admin_stats_total_users");
        Cache::forget("admin_stats_new_registrations_7d");
        Cache::forget("admin_stats_prev_week_registrations");
        // --- END OF WHISTLEBLOWER ---

        return redirect()->route('primary_pharmacist.pharmacists.index')->with('success', 'Pharmacist deleted successfully.');
    }
}