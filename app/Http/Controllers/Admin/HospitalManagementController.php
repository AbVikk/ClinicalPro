<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Hospital;
use App\Models\User;

class HospitalManagementController extends Controller
{
    /**
     * Display a listing of hospitals.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $hospitals = Hospital::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.hospitals.index', compact('hospitals'));
    }

    /**
     * Show the form for creating a new hospital.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.hospitals.create');
    }

    /**
     * Store a newly created hospital in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'domain_prefix' => 'required|string|unique:hospitals,domain_prefix|max:50',
            'address' => 'required|string',
            'contact_email' => 'required|email',
            'phone' => 'required|string',
            'subscription_plan' => 'required|in:basic,premium,enterprise',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $hospital = Hospital::create([
                'name' => $request->name,
                'domain_prefix' => $request->domain_prefix,
                'address' => $request->address,
                'contact_email' => $request->contact_email,
                'phone' => $request->phone,
                'subscription_plan' => $request->subscription_plan,
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            return redirect()->route('super_admin.hospitals.index')
                ->with('success', 'Hospital created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create hospital. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified hospital.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $hospital = Hospital::findOrFail($id);
        $admins = User::where('hospital_id', $hospital->id)
            ->where('role', 'admin')
            ->get();
            
        return view('admin.hospitals.show', compact('hospital', 'admins'));
    }

    /**
     * Show the form for editing the specified hospital.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $hospital = Hospital::findOrFail($id);
        return view('admin.hospitals.edit', compact('hospital'));
    }

    /**
     * Update the specified hospital in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $hospital = Hospital::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'domain_prefix' => 'required|string|unique:hospitals,domain_prefix,' . $hospital->id . '|max:50',
            'address' => 'required|string',
            'contact_email' => 'required|email',
            'phone' => 'required|string',
            'subscription_plan' => 'required|in:basic,premium,enterprise',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $hospital->update([
                'name' => $request->name,
                'domain_prefix' => $request->domain_prefix,
                'address' => $request->address,
                'contact_email' => $request->contact_email,
                'phone' => $request->phone,
                'subscription_plan' => $request->subscription_plan,
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            return redirect()->route('super_admin.hospitals.index')
                ->with('success', 'Hospital updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update hospital. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified hospital from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $hospital = Hospital::findOrFail($id);
        
        // Check if hospital has users
        if ($hospital->users()->count() > 0) {
            return redirect()->route('super_admin.hospitals.index')
                ->with('error', 'Cannot delete hospital with existing users. Please remove all users first.');
        }
        
        try {
            $hospital->delete();
            
            return redirect()->route('super_admin.hospitals.index')
                ->with('success', 'Hospital deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('super_admin.hospitals.index')
                ->with('error', 'Failed to delete hospital. Please try again.');
        }
    }
}