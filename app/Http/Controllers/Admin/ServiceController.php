<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    /**
     * Display a listing of all services for the Admin to manage.
     */
    public function index()
    {
        // Fetch all services, including inactive ones, ordered by name.
        $services = Service::withoutGlobalScope('active')->orderBy('service_name')->paginate(10);
        
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created service in the database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'service_name' => ['required', 'string', 'max:255', 'unique:hospital_services'],
            'service_type' => ['required', 'string', 'max:100'],
            // Ensure price is a valid decimal number greater than 0
            'price_amount' => ['required', 'numeric', 'min:0.01'],
            'price_currency' => ['required', 'string', 'size:3'], // e.g., NGN
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        Service::create($validatedData);

        return redirect()->route('admin.services.index')->with('success', 'Service and pricing created successfully.');
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified service in the database.
     */
    public function update(Request $request, Service $service)
    {
        $validatedData = $request->validate([
            // Rule to ignore the current service name during unique check
            'service_name' => ['required', 'string', 'max:255', Rule::unique('hospital_services')->ignore($service->id)],
            'service_type' => ['required', 'string', 'max:100'],
            'price_amount' => ['required', 'numeric', 'min:0.01'],
            'price_currency' => ['required', 'string', 'size:3'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        // If is_active is not present in the request (e.g., from a checkbox), default it to false
        $validatedData['is_active'] = $request->has('is_active') ? true : false;

        $service->update($validatedData);

        return redirect()->route('admin.services.index')->with('success', 'Service pricing and details updated successfully.');
    }

    /**
     * Remove the specified service from the database (soft delete is often preferred in real apps).
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service removed from the catalog.');
    }
    
    /**
     * Display all services
     */
    public function showAll()
    {
        $services = Service::all();
        return view('admin.services', compact('services'));
    }
    
    /**
     * API endpoint to fetch all active services
     */
    public function apiIndex()
    {
        $services = Service::all();
        return response()->json($services);
    }
    
    /**
     * Test service functionality
     */
    public function test()
    {
        $services = Service::all();
        return view('admin.services.test', compact('services'));
    }
}