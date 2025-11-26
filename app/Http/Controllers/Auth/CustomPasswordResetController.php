<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\OtpEmail;
use Carbon\Carbon;

class CustomPasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email not found in our records.'
            ]);
        }

        $otp = rand(1000, 9999);
        
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
                'expires_at' => now()->addMinutes(5)
            ]
        );

        // --- FIX: Send Email ---
        try {
            Mail::to($request->email)->send(new OtpEmail($otp, 'Password Reset'));
        } catch (\Exception $e) {
            Log::error("Failed to send reset OTP: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to send email.']);
        }
        // -----------------------

        return response()->json([
            'status' => 'success',
            'message' => 'Reset code sent successfully!',
            'redirect' => route('password.show-otp', ['email' => $request->email])
        ]);
    }

    public function showOtpForm(Request $request)
    {
        $email = $request->get('email');
        
        $resetRecord = DB::table('password_reset_tokens')->where('email', $email)->first();
        
        if (!$resetRecord) {
            return redirect()->route('password.request')->withErrors(['email' => 'Invalid reset request']);
        }
        
        if (isset($resetRecord->expires_at) && Carbon::parse($resetRecord->expires_at)->isPast()) {
            return redirect()->route('password.request')->withErrors(['email' => 'OTP has expired. Please request a new one.']);
        }
        
        // Calculate remaining seconds
        $expiresAt = Carbon::parse($resetRecord->expires_at);
        $remainingSeconds = now()->diffInSeconds($expiresAt, false);
        
        return view('auth.forgot-otp', [
            'email' => $email,
            'remaining_seconds' => $remainingSeconds
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:4'
        ]);

        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        
        if (!$resetRecord) {
            return response()->json(['success' => false, 'message' => 'Invalid reset request.']);
        }
        
        if (isset($resetRecord->expires_at) && Carbon::parse($resetRecord->expires_at)->isPast()) {
            return response()->json(['success' => false, 'message' => 'OTP has expired. Please request a new one.']);
        }
        
        if (!Hash::check($request->otp, $resetRecord->token)) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP. Please try again.']);
        }
        
        $token = Str::random(60);
        
        DB::table('password_reset_tokens')->where('email', $request->email)->update([
            'token' => Hash::make($token),
            'created_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'redirect' => route('password.reset', $token) . '?email=' . urlencode($request->email) . '&token=' . $token
        ]);
    }
    
    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Email not found in our records.']);
        }

        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        
        if ($resetRecord && isset($resetRecord->created_at)) {
            $lastRequestTime = Carbon::parse($resetRecord->created_at);
            if ($lastRequestTime->addMinute()->isFuture()) {
                return response()->json(['status' => 'error', 'message' => 'Please wait before requesting another OTP.']);
            }
        }

        $otp = rand(1000, 9999);
        
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($otp),
                'created_at' => now(),
                'expires_at' => now()->addMinutes(5)
            ]
        );

        // --- FIX: Send Email ---
        try {
            Mail::to($request->email)->send(new OtpEmail($otp, 'Password Reset'));
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to send email.']);
        }
        // -----------------------

        return response()->json([
            'status' => 'success',
            'message' => 'New OTP sent successfully!'
        ]);
    }
    
    public function showResetForm(Request $request, $token = null)
    {
        $email = $request->get('email');
        $resetRecord = DB::table('password_reset_tokens')->where('email', $email)->first();
        
        if (!$resetRecord) {
            return redirect()->route('password.request')->withErrors(['email' => 'Invalid reset request']);
        }
        
        return view('auth.reset-password', ['email' => $email, 'token' => $token]);
    }

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

        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        
        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return response()->json(['success' => false, 'message' => 'Invalid token.']);
        }

        $user = DB::table('users')->where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }
        
        DB::table('users')->where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);
        
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        
        return response()->json(['success' => true, 'message' => 'Password reset successfully!']);
    }
}