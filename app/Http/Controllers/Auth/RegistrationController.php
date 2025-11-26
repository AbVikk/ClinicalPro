<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    public function showInitialForm()
    {
        return view('auth.register-initial');
    }

    public function processInitialForm(Request $request)
    {
        $existingUser = DB::table('users')->where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

        if ($existingUser) {
            $existingRegistration = session('registration_data');
            if ($existingRegistration && 
                ($existingRegistration['email'] == $request->email || 
                 $existingRegistration['phone'] == $request->phone)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Continuing with existing registration.',
                    'redirect' => $this->getRedirectUrlForExistingRegistration($existingRegistration)
                ]);
            } else {
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

        $otp = rand(1000, 9999);
        
        // --- FIX: Send Real Email ---
        try {
            Mail::to($request->email)->send(new OtpEmail($otp, 'Registration Verification'));
        } catch (\Exception $e) {
            Log::error("Failed to send registration OTP: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to send email. Please check your connection.'], 500);
        }
        // ----------------------------
        
        $registrationData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'otp' => Hash::make($otp),
            'expires_at' => now()->addMinutes(5),
            'created_at' => now(),
        ];
        
        session(['registration_data' => $registrationData]);

        return response()->json([
            'status' => 'success',
            'message' => 'Verification code sent to your email.',
            'redirect' => route('register.otp')
        ]);
    }

    public function showOtpForm()
    {
        if (!session('registration_data')) {
            return redirect()->route('register.initial')->withErrors(['error' => 'Registration session expired.']);
        }
        
        $registrationData = session('registration_data');
        $expiresAt = Carbon::parse($registrationData['expires_at']);
        
        // FIX 1: Calculate exact seconds remaining (can be negative if expired)
        $remainingSeconds = now()->diffInSeconds($expiresAt, false);

        // If already expired, force a clean state
        if ($remainingSeconds <= 0) {
            session()->forget('registration_data');
            return redirect()->route('register.initial')->withErrors(['error' => 'OTP has expired. Please start again.']);
        }
        
        return view('auth.register-otp', [
            'email' => $registrationData['email'],
            'remaining_seconds' => $remainingSeconds // <-- Passing this to View
        ]);
    }

    public function resendOtp(Request $request)
    {
        if (!session('registration_data')) {
            return response()->json(['status' => 'error', 'message' => 'Session expired.'], 400);
        }

        $data = session('registration_data');
        
        // Generate new OTP
        $otp = rand(1000, 9999);
        
        // Update Session
        $data['otp'] = Hash::make($otp);
        $data['expires_at'] = now()->addMinutes(5); // Extend time
        session(['registration_data' => $data]);

        // Send Email
        try {
            Mail::to($data['email'])->send(new OtpEmail($otp, 'Resend Registration Code'));
            return response()->json(['status' => 'success', 'message' => 'New code sent!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to send email.'], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:4'
        ]);

        if (!session('registration_data')) {
            return response()->json([
                'success' => false,
                'message' => 'Registration session expired. Please start again.'
            ]);
        }
        
        $registrationData = session('registration_data');
        
        if (Carbon::parse($registrationData['expires_at'])->isPast()) {
            session()->forget('registration_data');
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please start registration again.'
            ]);
        }
        
        if (!Hash::check($request->otp, $registrationData['otp'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.'
            ]);
        }
        
        $registrationData['email_verified_at'] = now();
        session(['registration_data' => $registrationData]);
        
        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully!',
            'redirect' => route('register.continue')
        ]);
    }

    public function showContinueForm()
    {
        if (!session('registration_data')) {
            return redirect()->route('register.initial')->withErrors(['error' => 'Registration session expired. Please start again.']);
        }
        
        $registrationData = session('registration_data');
        
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

    public function processContinueForm(Request $request)
    {
        $request->validate([
            'gender' => 'required|string|in:male,female,other',
            'address' => 'required|string|max:500',
            'date_of_birth' => 'required|date',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!session('registration_data')) {
            return response()->json([
                'success' => false,
                'message' => 'Registration session expired. Please start again.'
            ]);
        }
        
        $registrationData = session('registration_data');
        
        if (!isset($registrationData['email_verified_at'])) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your email first.'
            ]);
        }
        
        $generatedUserId = $this->generateUserId($registrationData['role']);
        
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
            'status' => 'active',
            'email_verified_at' => $registrationData['email_verified_at'],
            'registration_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $this->createRoleSpecificRecord($user, $registrationData['role'], $generatedUserId);
        
        session(['registration_user_id' => $generatedUserId]);
        $registrationData = session('registration_data');
        session(['registration_data' => $registrationData]);
        
        return response()->json([
            'success' => true,
            'message' => 'Registration details saved successfully!',
            'redirect' => route('register.photo', ['user_id' => $generatedUserId])
        ]);
    }

    public function showPhotoForm(Request $request)
    {
        if (!session('registration_data')) {
            return redirect()->route('register.initial')->withErrors(['error' => 'Registration session expired. Please start again.']);
        }
        
        $registrationData = session('registration_data');
        $userId = session('registration_user_id');
        if (!$userId) {
            return redirect()->route('register.continue')->withErrors(['error' => 'Please complete previous steps first.']);
        }
        
        $user = DB::table('users')->where('user_id', $userId)->first();
        if (!$user) {
            return redirect()->route('register.continue')->withErrors(['error' => 'User not found.']);
        }
        
        return view('auth.register-photo', [
            'name' => $registrationData['name'],
            'user_id' => $userId
        ]);
    }

    public function processPhotoForm(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'photo' => 'nullable|image|max:2048',
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
        
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        } 
        elseif ($request->captured_image) {
            $image = $request->captured_image;
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageData = base64_decode($image);
            $filename = 'photo_' . $user->user_id . '_' . time() . '.png';
            $photoPath = 'photos/' . $filename;
            Storage::disk('public')->put($photoPath, $imageData);
        }
        
        if ($photoPath) {
            DB::table('users')->where('user_id', $user->user_id)->update([
                'photo' => $photoPath,
                'updated_at' => now(),
            ]);
        }
        
        if (in_array($user->role, ['doctor', 'clinic_staff'])) {
            return response()->json([
                'success' => true,
                'message' => 'Photo saved successfully!',
                'redirect' => route('register.proof', ['user_id' => $user->user_id])
            ]);
        } else {
            session()->forget(['registration_data', 'registration_user_id']);
            
            // --- OPTIONAL: SEND WELCOME EMAIL HERE ---
            try {
                $userModel = \App\Models\User::find($user->id);
                if($userModel) {
                     Mail::to($userModel->email)->send(new \App\Mail\WelcomeEmail($userModel));
                }
            } catch (\Exception $e) {}
            // -----------------------------------------

            return response()->json([
                'success' => true,
                'message' => 'Registration completed successfully!',
                'redirect' => route('login')
            ]);
        }
    }

    // ... [Proof, License, and Helper methods remain unchanged from your file] ...
    // ... [Keeping them concise for this response] ...
    
    public function showProofForm(Request $request)
    {
        $user = DB::table('users')->where('user_id', $request->user_id)->first();
        if (!$user || !in_array($user->role, ['doctor', 'clinic_staff'])) {
            return redirect()->route('login');
        }
        return view('auth.register-proof', ['user' => $user]);
    }

    public function processProofUpload(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'proof' => 'required|image|max:2048',
        ]);
        $user = DB::table('users')->where('user_id', $request->user_id)->first();
        if (!$user || !in_array($user->role, ['doctor', 'clinic_staff'])) {
            return response()->json(['success' => false, 'message' => 'Invalid request.']);
        }
        $proofPath = $request->file('proof')->store('proofs', 'public');
        $this->updateRoleProof($user->id, $user->role, $proofPath);
        return response()->json([
            'success' => true,
            'message' => 'Proof uploaded successfully!',
            'redirect' => route('register.license', ['user_id' => $user->user_id])
        ]);
    }

    public function showLicenseForm(Request $request)
    {
        $user = DB::table('users')->where('user_id', $request->user_id)->first();
        if (!$user || !in_array($user->role, ['doctor', 'clinic_staff'])) {
            return redirect()->route('login');
        }
        return view('auth.register-license', ['user' => $user]);
    }

    public function processLicenseForm(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'license_number' => 'required|string|max:100',
            'specialization' => 'required|string|max:100',
        ]);
        $user = DB::table('users')->where('user_id', $request->user_id)->first();
        if (!$user || !in_array($user->role, ['doctor', 'clinic_staff'])) {
            return response()->json(['success' => false, 'message' => 'Invalid request.']);
        }
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
        session()->forget(['registration_data', 'registration_user_id']);
        
        // --- OPTIONAL: SEND WELCOME EMAIL HERE FOR DOCTORS ---
        try {
            $userModel = \App\Models\User::find($user->id);
            if($userModel) {
                 Mail::to($userModel->email)->send(new \App\Mail\WelcomeEmail($userModel));
            }
        } catch (\Exception $e) {}
        // ----------------------------------------------------

        return response()->json([
            'success' => true,
            'message' => 'Professional details saved successfully!',
            'redirect' => route('login')
        ]);
    }

    private function generateUserId($role)
    {
        $prefix = '';
        switch ($role) {
            case 'doctor': $prefix = 'DOC'; break;
            case 'patient': $prefix = 'PAT'; break;
            case 'admin': $prefix = 'ADM'; break;
            case 'donor': $prefix = 'DON'; break;
            case 'clinic_staff': $prefix = 'CLI'; break;
            default: $prefix = 'USR';
        }
        return $prefix . '-' . time() . '-' . rand(1000, 9999);
    }

    private function getRedirectUrlForExistingRegistration($registrationData)
    {
        if (isset($registrationData['email_verified_at'])) {
            return route('register.continue');
        } elseif (isset($registrationData['otp'])) {
            return route('register.otp');
        } else {
            return route('register.initial');
        }
    }

    private function createRoleSpecificRecord($userId, $role, $userIdentifier)
    {
        $data = [
            'user_id' => $userId,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ];
        switch ($role) {
            case 'doctor':
                $data['doctor_id'] = $userIdentifier;
                DB::table('doctors_new')->insert($data);
                break;
            case 'clinic_staff':
                $data['staff_id'] = $userIdentifier;
                DB::table('clinic_staff')->insert($data);
                break;
            case 'admin':
                $data['admin_id'] = $userIdentifier;
                DB::table('admin')->insert($data);
                break;
        }
    }

    private function updateRoleProof($userId, $role, $proofPath)
    {
        $data = [
            'proof_of_identity' => $proofPath,
            'status' => 'pending_verification',
            'updated_at' => now(),
        ];
        switch ($role) {
            case 'doctor':
                DB::table('doctors_new')->where('user_id', $userId)->update($data);
                break;
            case 'clinic_staff':
                DB::table('clinic_staff')->where('user_id', $userId)->update($data);
                break;
        }
    }
}