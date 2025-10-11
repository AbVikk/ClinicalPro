<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    // Define user roles
    const ROLE_ADMIN = 'admin';
    const ROLE_HOD = 'hod';             // Head of Department
    const ROLE_MATRON = 'matron';       // Matron
    const ROLE_DOCTOR = 'doctor';
    const ROLE_NURSE = 'nurse';
    const ROLE_PATIENT = 'patient';
    const ROLE_DONOR = 'donor';
    const ROLE_BILLING_STAFF = 'billing_staff';
    const ROLE_PHARMACIST_PRIMARY = 'primary_pharmacist';
    const ROLE_PHARMACIST_SENIOR = 'senior_pharmacist';
    const ROLE_PHARMACIST_CLINIC = 'clinic_pharmacist';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'user_id',
        'gender',
        'address',
        'date_of_birth',
        'status',
        'city',
        'state',
        'zip_code',
        'country',
        'photo', // Add photo field
        'department_id', // Now fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    // Role checking methods
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isHOD()
    {
        return $this->role === self::ROLE_HOD;
    }

    public function isMatron()
    {
        return $this->role === self::ROLE_MATRON;
    }

    public function isDoctor()
    {
        return $this->role === self::ROLE_DOCTOR;
    }

    public function isNurse()
    {
        return $this->role === self::ROLE_NURSE;
    }

    public function isPatient()
    {
        return $this->role === self::ROLE_PATIENT;
    }

    public function isDonor()
    {
        return $this->role === self::ROLE_DONOR;
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        // This helper method needs to support multi-role checking via pipe delimiter if used
        if (str_contains($role, '|')) {
            $roles = explode('|', $role);
            return in_array($this->role, $roles);
        }
        return $this->role === $role;
    }

    // Relationships
    public function appointmentsAsDoctor()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function appointmentsAsPatient()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    // Prescription relationships for patients
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patient_id');
    }

    // New relationships for the updated database structure
    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    public function consultationsAsPatient()
    {
        return $this->hasMany(Consultation::class, 'patient_id');
    }

    public function consultationsAsDoctor()
    {
        return $this->hasMany(Consultation::class, 'doctor_id');
    }

    public function disbursements()
    {
        return $this->hasMany(Disbursement::class, 'recipient_id');
    }

    /**
     * Get the department the user belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the attendance records for the user.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    
    /**
     * Get the leave requests for the user.
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}