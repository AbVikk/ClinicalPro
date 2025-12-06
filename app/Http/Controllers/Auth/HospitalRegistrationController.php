<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Hospital;
use App\Models\User;

class HospitalRegistrationController extends Controller
{
    /**
     * Show the hospital registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.hospital-register-full');
    }

    /**
     * Handle hospital registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hospital_name' => 'required|string|max:255',
            'domain_prefix' => 'required|string|unique:hospitals,domain_prefix',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8|confirmed',
            'address' => 'required|string',
            'contact_email' => 'required|email',
            'phone' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::warning('Hospital registration validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['admin_password', 'admin_password_confirmation'])
            ]);
            
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            Log::info('Starting hospital registration process', [
                'hospital_name' => $request->hospital_name,
                'domain_prefix' => $request->domain_prefix,
                'admin_email' => $request->admin_email
            ]);
            
            // Create the hospital
            $hospital = Hospital::create([
                'name' => $request->hospital_name,
                'domain_prefix' => $request->domain_prefix,
                'address' => $request->address,
                'contact_email' => $request->contact_email,
                'phone' => $request->phone,
                'subscription_plan' => 'enterprise', // Default plan
                'is_active' => true,
            ]);

            // Create the hospital admin user
            $adminUser = User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'role' => 'admin',
                'hospital_id' => $hospital->id,
                'status' => 'active',
                'user_id' => 'ADM' . str_pad($hospital->id, 6, '0', STR_PAD_LEFT),
            ]);

            // Log in the admin user
            Auth::login($adminUser);
            
            Log::info('Hospital registered successfully', [
                'hospital_id' => $hospital->id,
                'hospital_name' => $hospital->name,
                'admin_user_id' => $adminUser->id,
                'admin_email' => $adminUser->email
            ]);

            return redirect()->route('admin.index')->with('success', 'Hospital registered successfully! Welcome to your new healthcare system.');
        } catch (\Exception $e) {
            Log::error('Hospital registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['admin_password', 'admin_password_confirmation'])
            ]);
            
            return redirect()->back()->with('error', 'Failed to register hospital. Please try again.')->withInput();
        }
    }
}