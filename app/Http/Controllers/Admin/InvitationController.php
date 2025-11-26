<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Category;
use App\Models\Department;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\InvitationEmail;
use App\Mail\WelcomeEmail;

class InvitationController extends Controller
{
    /**
     * Display the invitation creation form
     */
    public function create()
    {
        $roles = [
            'doctor' => 'Doctor',
            'clinic_staff' => 'Nurse',
            'patient' => 'Patient',
            'donor' => 'Donor',
            'primary_pharmacist' => 'Primary Pharmacist',
            'senior_pharmacist' => 'Senior Pharmacist',
            'clinic_pharmacist' => 'Clinic Pharmacist',
            'billing_staff' => 'Billing Staff',
            'hod' => 'Head of Department',
            'matron' => 'Matron'
        ];
        
        return view('admin.invitations.create', compact('roles'));
    }
    
    /**
     * Store a newly created invitation
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:invitations,email,NULL,id,used,false',
            'role' => 'required|in:doctor,clinic_staff,patient,donor,primary_pharmacist,clinic_pharmacist,senior_pharmacist,billing_staff,hod,matron',
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
    
    /**
     * Display the registration form for invited users
     */
    public function showRegistrationForm($token)
    {
        // Find the invitation
        $invitation = Invitation::where('token', $token)->first();
        
        // Check if invitation exists and is valid
        if (!$invitation || !$invitation->isValid()) {
            return redirect()->route('login')->with('error', 'Invalid or expired invitation link.');
        }
        
        // Load categories and departments for doctor registration form
        $categories = collect();
        $departments = collect();
        
        if ($invitation->role === 'doctor') {
            $categories = Category::all();
            $departments = Department::all();
        }
        
        return view('auth.invitation-register', compact('invitation', 'categories', 'departments'));
    }
    
    /**
     * Process the registration for invited users
     */
    public function register(Request $request, $token)
    {
        // Find the invitation
        $invitation = Invitation::where('token', $token)->first();
        
        // Check if invitation exists and is valid
        if (!$invitation || !$invitation->isValid()) {
            return redirect()->route('login')->with('error', 'Invalid or expired invitation link.');
        }
        
        // Base validation rules (common fields)
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ];
        
        // Add role-specific validation rules
        if ($invitation->role === 'doctor') {
            $rules['license_number'] = 'required|string|unique:doctors_new,license_number';
            $rules['specialization_id'] = 'required|exists:categories,id';
            $rules['department_id'] = 'required|exists:departments,id';
        } else {
            // Default validation rules for other roles
            $rules['date_of_birth'] = 'required|date';
            $rules['gender'] = 'required|in:male,female,other';
        }
        
        // Validate the request
        $request->validate($rules);
        
        // Handle file uploads
        $photoPath = null;
        $proofPath = null;
        
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }
        
        if ($request->hasFile('proof_of_identity')) {
            $proofPath = $request->file('proof_of_identity')->store('proofs', 'public');
        }
        
        // Map invitation role to user role
        $userRole = $invitation->role;
        if ($userRole === 'clinic_staff') {
            $userRole = 'nurse';
        }
        
        $userData = [
            'name' => $request->name,
            'email' => $invitation->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $userRole,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'photo' => $photoPath,
            'status' => 'active', // Auto-activate invited users
            'email_verified_at' => now(), // Auto-verify invited users
        ];
        
        // Generate user_id based on role
        $userData['user_id'] = $this->generateUserId($invitation->role);
        
        // Create User
        $user = User::create($userData);
        
        // Create role-specific records if needed
        if ($invitation->role === 'doctor') {
            Doctor::create([
                'user_id' => $user->id,
                'doctor_id' => 'DOC' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                'license_number' => $request->license_number,
                'category_id' => $request->specialization_id,
                'department_id' => $request->department_id,
                'proof_of_identity' => $proofPath,
                'status' => 'pending', // Doctors might still need license verification
            ]);
        }
        
        // Mark invitation as used
        $invitation->markAsUsed();
        
        // --- WHISTLEBLOWER: Clear Cache ---
        Cache::forget("admin_stats_pending_invitations");
        Cache::forget("admin_stats_total_users");
        Cache::forget("admin_stats_new_registrations_7d");
        Cache::forget("admin_stats_prev_week_registrations");

        if ($userRole === 'doctor') {
            Cache::forget("admin_stats_available_doctors");
        } else if ($userRole === 'patient') {
            Cache::forget("admin_stats_new_patients_list");
        }
        
        // --- SEND WELCOME EMAIL ---
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
        } catch (\Exception $e) {
            // Don't block registration if welcome email fails
            Log::error("Failed to send welcome email: " . $e->getMessage());
        }
        
        // Log the user in
        Auth::login($user);
        
        // Redirect based on role
        return $this->redirectUserByRole($user);
    }
    
    /**
     * Redirect user based on their role
     */
    private function redirectUserByRole($user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.index');
            case 'doctor':
                return redirect()->route('doctor.dashboard');
            case 'clinic_staff':
            case 'nurse':
            case 'matron':
                return redirect()->route('nurse.dashboard'); // Ensure this route exists in your nurse/web routes
            case 'patient':
                return redirect()->route('patient.dashboard');
            case 'donor':
                return redirect()->route('donor.dashboard');
            case 'primary_pharmacist':
            case 'senior_pharmacist':
            case 'clinic_pharmacist':
                return redirect()->route('pharmacy.dashboard');
            case 'hod':
                return redirect()->route('hod.dashboard');
            default:
                return redirect()->route('login')->with('error', 'Unknown role. Please contact administrator.');
        }
    }
    
    /**
     * Generate user ID based on role
     */
    private function generateUserId($role)
    {
        $prefixes = [
            'admin' => 'ADM',
            'doctor' => 'DOC',
            'nurse' => 'NUR',
            'patient' => 'PAT',
            'donor' => 'DON',
            'primary_pharmacist' => 'PHA',
            'senior_pharmacist' => 'PHA',
            'clinic_pharmacist' => 'PHA',
            'billing_staff' => 'BIL',
            'hod' => 'HOD',
            'matron' => 'MAT'
        ];
        
        $prefix = $prefixes[$role] ?? 'USR';
        
        $uniqueId = strtoupper(uniqid());
        $userId = $prefix . substr($uniqueId, -6);
        
        while (User::where('user_id', $userId)->exists()) {
            $uniqueId = strtoupper(uniqid());
            $userId = $prefix . substr($uniqueId, -6);
        }
        
        return $userId;
    }
    
    /**
     * Display a list of all invitations
     */
    public function index()
    {
        $invitations = Invitation::with('user')->latest()->get();
        return view('admin.invitations.index', compact('invitations'));
    }
    
    /**
     * Revoke an invitation
     */
    public function destroy(Invitation $invitation)
    {
        $invitation->delete();
        
        // Clear cache
        Cache::forget("admin_stats_pending_invitations");

        return redirect()->back()->with('success', 'Invitation revoked successfully.');
    }
}