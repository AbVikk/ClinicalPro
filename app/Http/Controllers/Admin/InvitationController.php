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

class InvitationController extends Controller
{
    /**
     * Display the invitation creation form
     */
    public function create()
    {
        $roles = [
            'doctor' => 'Doctor',
            'nurse' => 'Nurse',
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
            'role' => 'required|in:doctor,nurse,patient,donor,primary_pharmacist,clinic_pharmacist,senior_pharmacist,billing_staff,hod,matron',
        ]);
        
        // Create invitation token
        $token = Str::random(50);
        
        // Create invitation
        $invitation = Invitation::create([
            'token' => $token,
            'email' => $request->email,
            'role' => $request->role,
            'expires_at' => now()->addDays(7), // Expires in 7 days
        ]);
        
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
            $rules['medical_school'] = 'nullable|string|max:255';
            $rules['residency'] = 'nullable|string|max:255';
            $rules['fellowship'] = 'nullable|string|max:255';
            $rules['years_of_experience'] = 'nullable|integer|min:0';
            $rules['bio'] = 'nullable|string';
            $rules['date_of_birth'] = 'required|date';
            $rules['gender'] = 'required|in:male,female,other';
            $rules['address'] = 'required|string|max:255';
            $rules['city'] = 'required|string|max:255';
            $rules['state'] = 'required|string|max:255';
            $rules['zip_code'] = 'required|string|max:20';
            $rules['country'] = 'required|string|max:255';
            $rules['photo'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
            $rules['proof_of_identity'] = 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048';
        } else {
            // Default validation rules for other roles
            $rules['date_of_birth'] = 'required|date';
            $rules['gender'] = 'required|in:male,female,other';
            $rules['address'] = 'required|string|max:255';
            $rules['city'] = 'required|string|max:255';
            $rules['state'] = 'required|string|max:255';
            $rules['zip_code'] = 'required|string|max:20';
            $rules['country'] = 'required|string|max:255';
            $rules['photo'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
            $rules['proof_of_identity'] = 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048';
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
        $user = User::create([
            'name' => $request->name,
            'email' => $invitation->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $invitation->role,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'photo' => $photoPath,
            'status' => 'pending', // Default status
        ]);
        
        // Create role-specific records if needed
        if ($invitation->role === 'doctor') {
            Doctor::create([
                'user_id' => $user->id,
                'doctor_id' => 'DOC' . str_pad($user->id, 6, '0', STR_PAD_LEFT), // Generate doctor ID
                'license_number' => $request->license_number,
                'category_id' => $request->specialization_id,
                'department_id' => $request->department_id,
                'medical_school' => $request->medical_school,
                'residency' => $request->residency,
                'fellowship' => $request->fellowship,
                'years_of_experience' => $request->years_of_experience,
                'bio' => $request->bio,
                'proof_of_identity' => $proofPath,
                'status' => 'pending',
            ]);
        }
        
        // Mark invitation as used
        $invitation->markAsUsed();
        
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
        return redirect()->back()->with('success', 'Invitation revoked successfully.');
    }
}