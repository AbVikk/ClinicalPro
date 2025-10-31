<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Category;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Support\Facades\Cache; // <-- ADD THIS "WHISTLEBLOWER" IMPORT

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
        ]);
        
        // --- THIS IS THE "WHISTLEBLOWER" ---
        // A new invitation was created, so the "pending invitations" count is wrong.
        Cache::forget("admin_stats_pending_invitations");
        // --- END OF WHISTLEBLOWER ---
        
        // Generate the registration URL
        $registrationUrl = URL::temporarySignedRoute(
            'invitations.register',
            now()->addDays(7),
            ['token' => $invitation->token]
        );
        
        return redirect()->back()->with('success', 'Invitation created successfully!')->with('registration_url', $registrationUrl);
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
            // ... (other doctor rules)
        } else {
            // Default validation rules for other roles
            $rules['date_of_birth'] = 'required|date';
            $rules['gender'] = 'required|in:male,female,other';
            // ... (other common rules)
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
        
        // Create the user with the role from invitation
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
            'status' => 'pending', // Default status
        ];
        
        // Generate user_id based on role
        $userData['user_id'] = $this->generateUserId($invitation->role);
        
        $user = User::create($userData);
        
        // Create role-specific records if needed
        if ($invitation->role === 'doctor') {
            Doctor::create([
                'user_id' => $user->id,
                'doctor_id' => 'DOC' . str_pad($user->id, 6, '0', STR_PAD_LEFT), // Generate doctor ID
                'license_number' => $request->license_number,
                'category_id' => $request->specialization_id,
                'department_id' => $request->department_id,
                // ... (other doctor fields)
                'proof_of_identity' => $proofPath,
                'status' => 'pending',
            ]);
        }
        
        // Mark invitation as used
        $invitation->markAsUsed();
        
        // --- THIS IS THE "WHISTLEBLOWER" ---
        // This is a big one! A user was created AND an invitation was used.
        // We have to erase all the "whiteboard" answers this affects.
        
        // 1. An invitation was used
        Cache::forget("admin_stats_pending_invitations");
        
        // 2. A new user was created
        Cache::forget("admin_stats_total_users");
        Cache::forget("admin_stats_new_registrations_7d");
        Cache::forget("admin_stats_prev_week_registrations");

        // 3. Check the *specific* role and clear those lists too
        if ($userRole === 'doctor') {
            Cache::forget("admin_stats_available_doctors");
        } else if ($userRole === 'patient') {
            Cache::forget("admin_stats_new_patients_list");
        }
        // --- END OF WHISTLEBLOWER ---
        
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
                return redirect()->route('clinic.dashboard');
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
        // Map roles to prefixes
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
        
        $prefix = $prefixes[$role] ?? 'USR'; // Default prefix
        
        // Generate a unique user_id
        $uniqueId = strtoupper(uniqid());
        $userId = $prefix . substr($uniqueId, -6);
        
        // Ensure the user_id is unique
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
        
        // --- THIS IS THE "WHISTLEBLOWER" ---
        // An invitation was deleted, so the "pending invitations" count is wrong.
        Cache::forget("admin_stats_pending_invitations");
        // --- END OF WHISTLEBLOWER ---

        return redirect()->back()->with('success', 'Invitation revoked successfully.');
    }
}
