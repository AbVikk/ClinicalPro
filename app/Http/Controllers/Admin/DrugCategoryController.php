<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DrugCategory;

class DrugCategoryController extends Controller
{
    /**
     * Display a listing of the drug categories.
     */
    public function index()
    {
        $categories = DrugCategory::all();
        return view('admin.pharmacy.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new drug category.
     */
    public function create()
    {
        return view('admin.pharmacy.categories.create');
    }

    /**
     * Store a newly created drug category in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:drug_categories',
            'description' => 'nullable|string',
        ]);

        DrugCategory::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.pharmacy.categories.index')
            ->with('success', 'Drug category created successfully.');
    }

    /**
     * Show the form for editing the specified drug category.
     */
    public function edit(DrugCategory $category)
    {
        return view('admin.pharmacy.categories.edit', compact('category'));
    }

    /**
     * Update the specified drug category in storage.
     */
    public function update(Request $request, DrugCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:drug_categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.pharmacy.categories.index')
            ->with('success', 'Drug category updated successfully.');
    }

    /**
     * Remove the specified drug category from storage.
     */
    public function destroy(DrugCategory $category)
    {
        // Check if the category is being used by any drugs
        if ($category->drugs()->count() > 0) {
            return redirect()->route('admin.pharmacy.categories.index')
                ->with('error', 'Cannot delete category because it is being used by drugs.');
        }

        $category->delete();

        return redirect()->route('admin.pharmacy.categories.index')
            ->with('success', 'Drug category deleted successfully.');
    }
}