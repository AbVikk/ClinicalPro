<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    /**
     * Show the initial registration form (email, phone, role)
     */
    public function showInitialForm()
    {
        return view('auth.register-initial');
    }

    /**
     * Process the initial registration form
     */
    public function processInitialForm(Request $request)
    {
        // Check if user already exists with the same email or phone
        $existingUser = DB::table('users')->where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

        if ($existingUser) {
            // Check if there's an incomplete registration session for this user
            $existingRegistration = session('registration_data');
            if ($existingRegistration && 
                ($existingRegistration['email'] == $request->email || 
                 $existingRegistration['phone'] == $request->phone)) {
                // Continue with existing registration
                return response()->json([
                    'status' => 'success',
                    'message' => 'Continuing with existing registration.',
                    'redirect' => $this->getRedirectUrlForExistingRegistration($existingRegistration)
                ]);
            } else {
                // User already exists in database
                return response()->json([
                    'status' => 'error',
                    'message' => 'A user with this email or phone number already exists.'
                ]);
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:patient,doctor,nurse,admin,donor',
        ]);

        // Generate a random 4-digit OTP
        $otp = rand(1000, 9999);
        
        // Log the OTP for testing purposes
        Log::info('Registration OTP for ' . $request->email . ': ' . $otp);
        
        // Store registration data temporarily with OTP
        $registrationData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'otp' => Hash::make($otp),
            'expires_at' => now()->addMinutes(5), // OTP expires in 5 minutes
            'created_at' => now(),
        ];
        
        // Store in session or database (using session for simplicity)
        session(['registration_data' => $registrationData]);

        return response()->json([
            'status' => 'success',
            'message' => 'Registration data saved. Please verify OTP.',
            'redirect' => route('register.otp')
        ]);
    }

    /**
     * Show the OTP verification form
     */
    public function showOtpForm()
    {
        // Check if registration data exists in session
        if (!session('registration_data')) {
            return redirect()->route('register.initial')->withErrors(['error' => 'Registration session expired. Please start again.']);
        }
        
        $registrationData = session('registration_data');
        
        // Check if OTP has expired
        if (Carbon::parse($registrationData['expires_at'])->isPast()) {
            session()->forget('registration_data');
            return redirect()->route('register.initial')->withErrors(['error' => 'OTP has expired. Please start registration again.']);
        }
        
        return view('auth.register-otp', ['email' => $registrationData['email']]);
    }

    /**
     * Verify the OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:4'
        ]);

        // Check if registration data exists in session
        if (!session('registration_data')) {
            return response()->json([
                'success' => false,
                'message' => 'Registration session expired. Please start again.'
            ]);
        }
        
        $registrationData = session('registration_data');
        
        // Check if OTP has expired
        if (Carbon::parse($registrationData['expires_at'])->isPast()) {
            session()->forget('registration_data');
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please start registration again.'
            ]);
        }
        
        // Verify OTP
        if (!Hash::check($request->otp, $registrationData['otp'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.'
            ]);
        }
        
        // OTP verified, mark as verified in session
        $registrationData['email_verified_at'] = now();
        session(['registration_data' => $registrationData]);
        
        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully!',
            'redirect' => route('register.continue')
        ]);
    }

    /**
     * Show the continuation registration form
     */
    public function showContinueForm()
    {
        // Check if registration data exists in session
        if (!session('registration_data')) {
            return redirect()->route('register.initial')->withErrors(['error' => 'Registration session expired. Please start again.']);
        }
        
        $registrationData = session('registration_data');
        
        // Check if email is verified
        if (!isset($registrationData['email_verified_at'])) {
            return redirect()->route('register.otp')->withErrors(['error' => 'Please verify your email first.']);
        }
        
        return view('auth.register-continue', [
            'name' => $registrationData['name'],
            'email' => $registrationData['email'],
            'phone' => $registrationData['phone'],
            'role' => $registrationData['role']
        ]);
    }

    /**
     * Process the continuation registration form
     */
    public function processContinueForm(Request $request)
    {
        $request->validate([
            'gender' => 'required|string|in:male,female,other',
            'address' => 'required|string|max:500',
            'date_of_birth' => 'required|date',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if registration data exists in session
        if (!session('registration_data')) {
            return response()->json([
                'success' => false,
                'message' => 'Registration session expired. Please start again.'
            ]);
        }
        
        $registrationData = session('registration_data');
        
        // Check if email is verified
        if (!isset($registrationData['email_verified_at'])) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your email first.'
            ]);
        }
        
        // Generate user ID based on role
        $generatedUserId = $this->generateUserId($registrationData['role']);
        
        // Create user in database
        $user = DB::table('users')->insertGetId([
            'name' => $registrationData['name'],
            'email' => $registrationData['email'],
            'phone' => $registrationData['phone'],
            'user_id' => $generatedUserId,
            'gender' => $request->gender,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'password' => Hash::make($request->password),
            'role' => $registrationData['role'],
            'status' => 'active', // Default status
            'email_verified_at' => $registrationData['email_verified_at'],
            'registration_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Create role-specific record
        $this->createRoleSpecificRecord($user, $registrationData['role'], $generatedUserId);
        
        // Store generated user_id in session for next step
        session(['registration_user_id' => $generatedUserId]);
        
        // Clear registration session data except user ID
        $registrationData = session('registration_data');
        session(['registration_data' => $registrationData]);
        
        return response()->json([
            'success' => true,
            'message' => 'Registration details saved successfully!',
            'redirect' => route('register.photo', ['user_id' => $generatedUserId])
        ]);
    }

    /**
     * Show the photo capture form
     */
    public function showPhotoForm(Request $request)
    {
        // Check if registration data exists in session
        if (!session('registration_data')) {
            return redirect()->route('register.initial')->withErrors(['error' => 'Registration session expired. Please start again.']);
        }
        
        $registrationData = session('registration_data');
        
        // Check if user ID exists in session
        $userId = session('registration_user_id');
        if (!$userId) {
            return redirect()->route('register.continue')->withErrors(['error' => 'Please complete previous steps first.']);
        }
        
        // Get user by generated user_id
        $user = DB::table('users')->where('user_id', $userId)->first();
        if (!$user) {
            return redirect()->route('register.continue')->withErrors(['error' => 'User not found.']);
        }
        
        return view('auth.register-photo', [
            'name' => $registrationData['name'],
            'user_id' => $userId
        ]);
    }

    /**
     * Process the photo capture
     */
    public function processPhotoForm(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'photo' => 'nullable|image|max:2048', // Max 2MB
            'captured_image' => 'nullable|string'
        ]);

        $user = DB::table('users')->where('user_id', $request->user_id)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.'
            ]);
        }
        
        $photoPath = null;
        
        // Handle uploaded file
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        } 
        // Handle captured image from webcam
        elseif ($request->captured_image) {
            // Decode base64 image
            $image = $request->captured_image;
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageData = base64_decode($image);
            
            // Generate unique filename
            $filename = 'photo_' . $user->user_id . '_' . time() . '.png';
            $photoPath = 'photos/' . $filename;
            
            // Store the image
            Storage::disk('public')->put($photoPath, $imageData);
        }
        
        // Update user with photo path
        if ($photoPath) {
            DB::table('users')->where('user_id', $user->user_id)->update([
                'photo' => $photoPath,
                'updated_at' => now(),
            ]);
        }
        
        // Check if user needs to upload proof (doctors, clinic staff)
        if (in_array($user->role, ['doctor', 'clinic_staff'])) {
            return response()->json([
                'success' => true,
                'message' => 'Photo saved successfully!',
                'redirect' => route('register.proof', ['user_id' => $user->user_id])
            ]);
        } else {
            // Clear registration session for non-doctor/clinic staff users
            session()->forget(['registration_data', 'registration_user_id']);
            
            return response()->json([
                'success' => true,
                'message' => 'Registration completed successfully!',
                'redirect' => route('login')
            ]);
        }
    }

    /**
     * Show the proof upload form
     */
    public function showProofForm(Request $request)
    {
        $user = DB::table('users')->where('user_id', $request->user_id)->first();
        
        if (!$user || !in_array($user->role, ['doctor', 'clinic_staff'])) {
            return redirect()->route('login');
        }
        
        return view('auth.register-proof', ['user' => $user]);
    }

    /**
     * Process the proof upload
     */
    public function processProofUpload(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'proof' => 'required|image|max:2048', // Max 2MB
        ]);

        $user = DB::table('users')->where('user_id', $request->user_id)->first();
        
        if (!$user || !in_array($user->role, ['doctor', 'clinic_staff'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.'
            ]);
        }
        
        // Store the proof image
        $proofPath = $request->file('proof')->store('proofs', 'public');
        
        // Update role-specific table with proof
        $this->updateRoleProof($user->id, $user->role, $proofPath);
        
        return response()->json([
            'success' => true,
            'message' => 'Proof uploaded successfully!',
            'redirect' => route('register.license', ['user_id' => $user->user_id])
        ]);
    }

    /**
     * Show the license and specialization form
     */
    public function showLicenseForm(Request $request)
    {
        $user = DB::table('users')->where('user_id', $request->user_id)->first();
        
        if (!$user || !in_array($user->role, ['doctor', 'clinic_staff'])) {
            return redirect()->route('login');
        }
        
        return view('auth.register-license', ['user' => $user]);
    }

    /**
     * Process the license and specialization form
     */
    public function processLicenseForm(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'license_number' => 'required|string|max:100',
            'specialization' => 'required|string|max:100',
        ]);

        $user = DB::table('users')->where('user_id', $request->user_id)->first();
        
        if (!$user || !in_array($user->role, ['doctor', 'clinic_staff'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request.'
            ]);
        }
        
        // Update role-specific table with license and specialization
        switch ($user->role) {
            case 'doctor':
                DB::table('doctors_new')->where('user_id', $user->id)->update([
                    'license_number' => $request->license_number,
                    'specialization' => $request->specialization,
                    'updated_at' => now(),
                ]);
                break;
            case 'clinic_staff':
                DB::table('clinic_staff')->where('user_id', $user->id)->update([
                    'license_number' => $request->license_number,
                    'specialization' => $request->specialization,
                    'updated_at' => now(),
                ]);
                break;
        }
        
        // Clear registration session
        session()->forget(['registration_data', 'registration_user_id']);
        
        return response()->json([
            'success' => true,
            'message' => 'Professional details saved successfully!',
            'redirect' => route('login')
        ]);
    }

    /**
     * Generate user ID based on role
     */
    private function generateUserId($role)
    {
        $prefix = '';
        switch ($role) {
            case 'doctor':
                $prefix = 'DOC';
                break;
            case 'patient':
                $prefix = 'PAT';
                break;
            case 'admin':
                $prefix = 'ADM';
                break;
            case 'donor':
                $prefix = 'DON';
                break;
            case 'clinic_staff':
                $prefix = 'CLI';
                break;
            default:
                $prefix = 'USR';
        }
        
        // Generate unique ID with timestamp and random number
        return $prefix . '-' . time() . '-' . rand(1000, 9999);
    }

    /**
     * Get redirect URL for existing registration based on current step
     */
    private function getRedirectUrlForExistingRegistration($registrationData)
    {
        // Check if email is verified
        if (isset($registrationData['email_verified_at'])) {
            // Email verified, redirect to continue form
            return route('register.continue');
        } elseif (isset($registrationData['otp'])) {
            // OTP generated but not verified, redirect to OTP form
            return route('register.otp');
        } else {
            // No OTP generated yet, redirect to initial form
            return route('register.initial');
        }
    }

    /**
     * Create role-specific record
     */
    private function createRoleSpecificRecord($userId, $role, $userIdentifier)
    {
        switch ($role) {
            case 'doctor':
                DB::table('doctors_new')->insert([
                    'user_id' => $userId,
                    'doctor_id' => $userIdentifier,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                break;
            case 'clinic_staff':
                DB::table('clinic_staff')->insert([
                    'user_id' => $userId,
                    'staff_id' => $userIdentifier,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                break;
            case 'admin':
                DB::table('admin')->insert([
                    'user_id' => $userId,
                    'admin_id' => $userIdentifier,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                break;
        }
    }

    /**
     * Update role proof
     */
    private function updateRoleProof($userId, $role, $proofPath)
    {
        switch ($role) {
            case 'doctor':
                DB::table('doctors_new')->where('user_id', $userId)->update([
                    'proof_of_identity' => $proofPath,
                    'status' => 'pending_verification',
                    'updated_at' => now(),
                ]);
                break;
            case 'clinic_staff':
                DB::table('clinic_staff')->where('user_id', $userId)->update([
                    'proof_of_identity' => $proofPath,
                    'status' => 'pending_verification',
                    'updated_at' => now(),
                ]);
                break;
        }
    }
}