<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Hospital;

class HospitalUserRegistrationController extends Controller
{
    /**
     * Show the user registration form for a specific hospital.
     *
     * @param  string  $hospitalDomain
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm($hospitalDomain)
    {
        try {
            $hospital = Hospital::where('domain_prefix', $hospitalDomain)->firstOrFail();
            return view('auth.hospital-user-register-full', compact('hospital'));
        } catch (\Exception $e) {
            Log::error('Hospital not found for user registration', [
                'domain_prefix' => $hospitalDomain,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('login')->with('error', 'Hospital not found. Please check the URL and try again.');
        }
    }

    /**
     * Handle user registration for a specific hospital.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $hospitalDomain
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request, $hospitalDomain)
    {
        try {
            $hospital = Hospital::where('domain_prefix', $hospitalDomain)->firstOrFail();
        } catch (\Exception $e) {
            Log::error('Hospital not found during user registration', [
                'domain_prefix' => $hospitalDomain,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('login')->with('error', 'Hospital not found. Please check the URL and try again.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:doctor,nurse,pharmacist,receptionist,accountant',
            'specialization' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            Log::warning('User registration validation failed', [
                'hospital_id' => $hospital->id,
                'hospital_name' => $hospital->name,
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['password', 'password_confirmation'])
            ]);
            
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Log::info('Starting user registration process', [
                'hospital_id' => $hospital->id,
                'hospital_name' => $hospital->name,
                'user_email' => $request->email,
                'user_role' => $request->role
            ]);
            
            // Create the user with hospital association
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'hospital_id' => $hospital->id,
                'status' => 'active',
                'user_id' => strtoupper(substr($request->role, 0, 3)) . str_pad($hospital->id, 4, '0', STR_PAD_LEFT) . rand(100, 999),
                'specialization' => $request->specialization,
                'department' => $request->department,
            ]);

            // Log in the user
            Auth::login($user);
            
            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'hospital_id' => $hospital->id,
                'hospital_name' => $hospital->name
            ]);

            // Redirect based on role
            $redirect = $this->redirectToDashboard($user);
            
            return $redirect->with('success', 'Registration successful! Welcome to ' . $hospital->name . '.');
            
        } catch (\Exception $e) {
            Log::error('User registration failed', [
                'hospital_id' => $hospital->id,
                'hospital_name' => $hospital->name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['password', 'password_confirmation'])
            ]);
            
            return redirect()->back()->with('error', 'Failed to register user. Please try again.')->withInput();
        }
    }

    /**
     * Redirect user to their appropriate dashboard based on role.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectToDashboard($user)
    {
        return match($user->role) {
            'admin' => redirect()->route('admin.index'),
            'doctor' => redirect()->route('doctor.index'),
            'nurse' => redirect()->route('nurse.index'),
            'pharmacist' => redirect()->route('primary_pharmacist.dashboard'),
            'receptionist' => redirect()->route('receptionist.index'),
            'accountant' => redirect()->route('accountant.index'),
            default => redirect()->route('home')
        };
    }
}