<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache; // <-- ADD THIS "WHISTLEBLOWER" IMPORT

class PharmacistController extends Controller
{
    /**
     * Display a listing of pharmacists.
     */
    public function index(Request $request)
    {
        $validPharmacistRoles = [
            'primary_pharmacist', 
            'senior_pharmacist', 
            'clinic_pharmacist'
        ];
        
        // Gets the current filter (e.g., 'primary_pharmacist' or null)
        $roleFilter = $request->get('role');
        
        // Get search term
        $search = $request->get('search');

        $pharmacists = User::whereIn('role', $validPharmacistRoles)
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
            return view('admin.pharmacists.partials.table', compact('pharmacists'))->render();
        }

        return view('admin.pharmacists.index', compact('pharmacists', 'pageTitle', 'roleFilter'));
    }

    /**
     * Display the specified pharmacist.
     */
    public function show(User $pharmacist)
    {
        // Ensure the user is a pharmacist
        $validPharmacistRoles = [
            'primary_pharmacist', 
            'senior_pharmacist', 
            'clinic_pharmacist'
        ];
        
        if (!in_array($pharmacist->role, $validPharmacistRoles)) {
            abort(404);
        }

        return view('admin.pharmacists.show', compact('pharmacist'));
    }

    /**
     * Update the specified pharmacist status.
     */
    public function update(Request $request, User $pharmacist)
    {
        // Ensure the user is a pharmacist
        $validPharmacistRoles = [
            'primary_pharmacist', 
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
        // Ensure the user is a pharmacist
        $validPharmacistRoles = [
            'primary_pharmacist', 
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

        return redirect()->route('admin.pharmacists.index')->with('success', 'Pharmacist deleted successfully.');
    }
}
