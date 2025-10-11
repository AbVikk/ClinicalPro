<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CustomPasswordResetController extends Controller
{
    /**
     * Send a reset link to the given user.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if user exists
        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email not found in our records.'
            ]);
        }

        // Generate a random 4-digit OTP
        $otp = rand(1000, 9999);
        
        // Log the OTP for testing purposes
        Log::info('Password reset OTP for ' . $request->email . ': ' . $otp);
        
        // Store OTP in password_reset_tokens table with expiration time (5 minutes from now)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($otp), // Store hashed OTP
                'created_at' => now(),
                'expires_at' => now()->addMinutes(5) // Set expiration time
            ]
        );

        // For demonstration, we'll return success and redirect to the OTP page
        // In a real application, you would send an email with the OTP
        return response()->json([
            'status' => 'success',
            'message' => 'Reset link sent successfully!',
            'redirect' => route('password.show-otp', ['email' => $request->email])
        ]);
    }

    /**
     * Show the OTP verification form
     */
    public function showOtpForm(Request $request)
    {
        $email = $request->get('email');
        
        // Check if email exists in password reset table
        $resetRecord = DB::table('password_reset_tokens')->where('email', $email)->first();
        
        if (!$resetRecord) {
            return redirect()->route('password.request')->withErrors(['email' => 'Invalid reset request']);
        }
        
        // Check if OTP has expired
        if (isset($resetRecord->expires_at) && Carbon::parse($resetRecord->expires_at)->isPast()) {
            return redirect()->route('password.request')->withErrors(['email' => 'OTP has expired. Please request a new one.']);
        }
        
        return view('auth.forgot-otp', ['email' => $email]);
    }

    /**
     * Verify the OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:4'
        ]);

        // Retrieve the stored OTP hash
        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        
        // Check if record exists
        if (!$resetRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid reset request.'
            ]);
        }
        
        // Check if OTP has expired
        if (isset($resetRecord->expires_at) && Carbon::parse($resetRecord->expires_at)->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.'
            ]);
        }
        
        // Verify OTP
        if (!Hash::check($request->otp, $resetRecord->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP. Please try again.'
            ]);
        }
        
        // Generate a temporary token for password reset
        $token = Str::random(60);
        
        // Store the token in the database
        DB::table('password_reset_tokens')->where('email', $request->email)->update([
            'token' => Hash::make($token),
            'created_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'redirect' => route('password.reset', $token) . '?email=' . urlencode($request->email) . '&token=' . $token
        ]);
    }
    
    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if user exists
        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email not found in our records.'
            ]);
        }

        // Check if there's a recent OTP (within last 1 minute)
        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        
        if ($resetRecord && isset($resetRecord->created_at)) {
            $lastRequestTime = Carbon::parse($resetRecord->created_at);
            if ($lastRequestTime->addMinute()->isFuture()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please wait before requesting another OTP.'
                ]);
            }
        }

        // Generate a random 4-digit OTP
        $otp = rand(1000, 9999);
        
        // Log the OTP for testing purposes
        Log::info('Password reset OTP (resend) for ' . $request->email . ': ' . $otp);
        
        // Store OTP in password_reset_tokens table with expiration time (5 minutes from now)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($otp), // Store hashed OTP
                'created_at' => now(),
                'expires_at' => now()->addMinutes(5) // Set expiration time
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'New OTP sent successfully!'
        ]);
    }
    
    /**
     * Display the password reset view.
     */
    public function showResetForm(Request $request, $token = null)
    {
        $email = $request->get('email');
        
        // Check if email exists in password reset table
        $resetRecord = DB::table('password_reset_tokens')->where('email', $email)->first();
        
        if (!$resetRecord) {
            return redirect()->route('password.request')->withErrors(['email' => 'Invalid reset request']);
        }
        
        return view('auth.reset-password', [
            'email' => $email,
            'token' => $token,
        ]);
    }

    /**
     * Reset the user's password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.'
        ]);

        // Check if the token exists
        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        
        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token.'
            ]);
        }

        // Update the user's password
        $user = DB::table('users')->where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);
        }
        
        DB::table('users')->where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);
        
        // Delete the password reset token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully!'
        ]);
    }
}