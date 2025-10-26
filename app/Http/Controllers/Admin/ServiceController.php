<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceTimePricing;
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
        $services = Service::withoutGlobalScope('active')->with('activeTimePricings')->orderBy('service_name')->paginate(10);
        
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
            'default_duration' => ['required', 'integer', 'in:30,40,60'],
            // Time pricing validation
            'time_pricing.*.duration' => ['required', 'integer', 'in:30,40,60'],
            'time_pricing.*.price' => ['required', 'numeric', 'min:0.01'],
        ]);

        // Create the service
        $service = Service::create([
            'service_name' => $validatedData['service_name'],
            'service_type' => $validatedData['service_type'],
            'price_amount' => $validatedData['price_amount'],
            'price_currency' => $validatedData['price_currency'],
            'description' => $validatedData['description'] ?? null,
            'is_active' => $request->has('is_active') ? true : false,
            'default_duration' => $validatedData['default_duration'],
        ]);

        // Create time-based pricing if provided
        if (isset($validatedData['time_pricing'])) {
            foreach ($validatedData['time_pricing'] as $pricing) {
                ServiceTimePricing::create([
                    'service_id' => $service->id,
                    'duration_minutes' => $pricing['duration'],
                    'price' => $pricing['price'],
                    'is_active' => true,
                ]);
            }
        }

        return redirect()->route('admin.services.index')->with('success', 'Service and pricing created successfully.');
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Service $service)
    {
        $service->load('timePricings');
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
            'default_duration' => ['required', 'integer', 'in:30,40,60'],
            // Time pricing validation
            'time_pricing.*.duration' => ['required', 'integer', 'in:30,40,60'],
            'time_pricing.*.price' => ['required', 'numeric', 'min:0.01'],
            'time_pricing.*.is_active' => ['sometimes', 'boolean'],
        ]);

        // Update the service
        $service->update([
            'service_name' => $validatedData['service_name'],
            'service_type' => $validatedData['service_type'],
            'price_amount' => $validatedData['price_amount'],
            'price_currency' => $validatedData['price_currency'],
            'description' => $validatedData['description'] ?? null,
            'is_active' => $request->has('is_active') ? true : false,
            'default_duration' => $validatedData['default_duration'],
        ]);

        // Handle time-based pricing
        if (isset($validatedData['time_pricing'])) {
            // Get existing pricing IDs
            $existingPricingIds = $service->timePricings->pluck('id')->toArray();
            $updatedPricingIds = [];
            
            foreach ($validatedData['time_pricing'] as $pricing) {
                if (isset($pricing['id'])) {
                    // Update existing pricing
                    $serviceTimePricing = ServiceTimePricing::find($pricing['id']);
                    if ($serviceTimePricing && $serviceTimePricing->service_id == $service->id) {
                        $serviceTimePricing->update([
                            'duration_minutes' => $pricing['duration'],
                            'price' => $pricing['price'],
                            'is_active' => $pricing['is_active'] ?? true,
                        ]);
                        $updatedPricingIds[] = $pricing['id'];
                    }
                } else {
                    // Create new pricing
                    $newPricing = ServiceTimePricing::create([
                        'service_id' => $service->id,
                        'duration_minutes' => $pricing['duration'],
                        'price' => $pricing['price'],
                        'is_active' => true,
                    ]);
                    $updatedPricingIds[] = $newPricing->id;
                }
            }
            
            // Delete removed pricing
            $pricingToDelete = array_diff($existingPricingIds, $updatedPricingIds);
            if (!empty($pricingToDelete)) {
                ServiceTimePricing::whereIn('id', $pricingToDelete)->delete();
            }
        }

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
        $services = Service::with('activeTimePricings')->get();
        return view('admin.services', compact('services'));
    }
    
    /**
     * API endpoint to fetch all active services
     */
    public function apiIndex()
    {
        $services = Service::with('activeTimePricings')->get();
        return response()->json($services);
    }
    
    /**
     * Test service functionality
     */
    public function test()
    {
        $services = Service::with('activeTimePricings')->get();
        return view('admin.services.test', compact('services'));
    }
}