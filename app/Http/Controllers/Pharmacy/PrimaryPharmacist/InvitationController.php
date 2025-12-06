<?php

namespace App\Http\Controllers\Pharmacy\PrimaryPharmacist;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Mail\InvitationEmail;

class InvitationController extends Controller
{
    /**
     * Display the invitation creation form for primary pharmacist
     */
    public function create()
    {
        // Only show senior pharmacist and clinic pharmacist roles
        $roles = [
            'senior_pharmacist' => 'Senior Pharmacist',
            'clinic_pharmacist' => 'Clinic Pharmacist'
        ];
        
        return view('pharmacy.primary_pharmacist.invitations.create', compact('roles'));
    }
    
    /**
     * Store a newly created invitation for primary pharmacist
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:invitations,email,NULL,id,used,false',
            'role' => 'required|in:senior_pharmacist,clinic_pharmacist',
        ]);
        
        // Check if invitation already exists for this email and is not used
        $existingInvitation = Invitation::where('email', $request->email)
            ->where('used', false)
            ->first();
            
        if ($existingInvitation) {
            return redirect()->back()->with('error', 'An invitation already exists for this email address.');
        }
        
        // Create invitation token
        $token = Str::random(50);
        
        // Create invitation
        $invitation = Invitation::create([
            'token' => $token,
            'email' => $request->email,
            'role' => $request->role,
            'hospital_id' => Auth::user()->hospital_id, // FIX: Add hospital_id
            'expires_at' => now()->addDays(7), // Expires in 7 days
            'invited_by' => Auth::id(), // Track who sent it
        ]);
        
        // --- WHISTLEBLOWER: Clear Cache ---
        Cache::forget("admin_stats_pending_invitations");
        
        // Generate the SIGNED registration URL (Required for security)
        $registrationUrl = URL::temporarySignedRoute(
            'invitations.register',
            now()->addDays(7),
            ['token' => $invitation->token]
        );
        
        // --- SEND EMAIL ---
        $message = 'Invitation created and email sent successfully!';
        
        try {
            $email = new InvitationEmail($invitation);
            // CRITICAL: Override the URL with the SIGNED version
            $email->url = $registrationUrl; 
            
            Mail::to($request->email)->send($email);
        } catch (\Exception $e) {
            Log::error("Failed to send invitation email: " . $e->getMessage());
            $message = 'Invitation created, but email failed. You can copy the link below.';
        }
        
        return redirect()->back()
            ->with('success', $message)
            ->with('registration_url', $registrationUrl);
    }
}