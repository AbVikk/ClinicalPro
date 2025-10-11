<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class MatronController extends Controller
{
    // Display a listing of Matrons.
    public function index()
    {
        try {
            $matrons = User::where('role', User::ROLE_MATRON)
                        ->with('department')
                        ->paginate(15);
            
            return view('admin.matrons.index', compact('matrons'))
                ->with('success', 'Matrons list loaded successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to load matrons list: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load matrons list. Please try again.');
        }
    }

    // Show the form for creating a new Matron.
    public function create()
    {
        try {
            $departments = Department::all();
            return view('admin.matrons.create', compact('departments'))
                ->with('success', 'Matron creation form loaded successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to load matron creation form: ' . $e->getMessage());
            return redirect()->route('admin.matrons.index')->with('error', 'Failed to load creation form. Please try again.');
        }
    }

    // Store a newly created Matron in storage.
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'department_id' => 'required|exists:departments,id',
            ]);

            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => User::ROLE_MATRON,
                'department_id' => $validated['department_id'],
            ]);
            
            return redirect()->route('admin.matrons.index')->with('success', 'Matron created and assigned to department successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to create matron: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create matron: ' . $e->getMessage())->withInput();
        }
    }
    
    // Show the form for editing the specified Matron.
    public function edit(User $matron)
    {
        try {
            if ($matron->role !== User::ROLE_MATRON) {
                return redirect()->route('admin.matrons.index')->with('error', 'User is not a matron.');
            }
            
            $departments = Department::all();
            return view('admin.matrons.edit', compact('matron', 'departments'))
                ->with('success', 'Matron edit form loaded successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to load matron edit form: ' . $e->getMessage());
            return redirect()->route('admin.matrons.index')->with('error', 'Failed to load edit form. Please try again.');
        }
    }
    
    // Update the specified Matron in storage.
    public function update(Request $request, User $matron)
    {
        try {
            if ($matron->role !== User::ROLE_MATRON) {
                return redirect()->route('admin.matrons.index')->with('error', 'User is not a matron.');
            }
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $matron->id,
                'department_id' => 'required|exists:departments,id',
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'department_id' => $validated['department_id'],
            ];
            
            // Only update password if provided
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $matron->update($updateData);
            
            return redirect()->route('admin.matrons.index')->with('success', 'Matron updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update matron: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update matron: ' . $e->getMessage())->withInput();
        }
    }
    
    // Remove the specified Matron from storage.
    public function destroy(User $matron)
    {
        try {
            if ($matron->role !== User::ROLE_MATRON) {
                return redirect()->route('admin.matrons.index')->with('error', 'User is not a matron.');
            }
            
            $matron->delete();
            
            return redirect()->route('admin.matrons.index')->with('success', 'Matron deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete matron: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete matron: ' . $e->getMessage());
        }
    }
}