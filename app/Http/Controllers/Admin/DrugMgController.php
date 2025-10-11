<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DrugMg;

class DrugMgController extends Controller
{
    /**
     * Display a listing of the drug mg values.
     */
    public function index()
    {
        $mgs = DrugMg::all();
        return view('admin.pharmacy.mg.index', compact('mgs'));
    }

    /**
     * Show the form for creating a new drug mg value.
     */
    public function create()
    {
        return view('admin.pharmacy.mg.create');
    }

    /**
     * Store a newly created drug mg value in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mg_value' => 'required|string|max:50|unique:drug_mg',
        ]);

        DrugMg::create([
            'mg_value' => $request->mg_value,
        ]);

        return redirect()->route('admin.pharmacy.mg.index')
            ->with('success', 'Drug mg value created successfully.');
    }

    /**
     * Show the form for editing the specified drug mg value.
     */
    public function edit(DrugMg $mg)
    {
        return view('admin.pharmacy.mg.edit', compact('mg'));
    }

    /**
     * Update the specified drug mg value in storage.
     */
    public function update(Request $request, DrugMg $mg)
    {
        $request->validate([
            'mg_value' => 'required|string|max:50|unique:drug_mg,mg_value,' . $mg->id,
        ]);

        $mg->update([
            'mg_value' => $request->mg_value,
        ]);

        return redirect()->route('admin.pharmacy.mg.index')
            ->with('success', 'Drug mg value updated successfully.');
    }

    /**
     * Remove the specified drug mg value from storage.
     */
    public function destroy(DrugMg $mg)
    {
        // Check if the mg value is being used by any drugs
        if ($mg->drugs()->count() > 0) {
            return redirect()->route('admin.pharmacy.mg.index')
                ->with('error', 'Cannot delete mg value because it is being used by drugs.');
        }

        $mg->delete();

        return redirect()->route('admin.pharmacy.mg.index')
            ->with('success', 'Drug mg value deleted successfully.');
    }
}