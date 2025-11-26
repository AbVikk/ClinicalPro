<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Carbon;

/**
 * @property string|null $photo
 * @property int $appointments_as_patient_count
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    // Define user roles
    const ROLE_ADMIN = 'admin';
    const ROLE_HOD = 'hod';
    const ROLE_MATRON = 'matron';
    const ROLE_DOCTOR = 'doctor';
    const ROLE_NURSE = 'nurse';
    const ROLE_PATIENT = 'patient';
    const ROLE_DONOR = 'donor';
    const ROLE_BILLING_STAFF = 'billing_staff';
    
    // Pharmacist Roles
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
        'photo', 
        'department_id',
        'email_verified_at',
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

    // Accessor for age_gender
    public function getAgeGenderAttribute()
    {
        $age = $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : 'N/A';
        $gender = $this->gender ? ucfirst($this->gender) : 'N/A';
        return "{$age} / {$gender}";
    }

    // --- ROLE CHECKING METHODS ---

    public function isAdmin() { return $this->role === self::ROLE_ADMIN; }
    public function isHOD() { return $this->role === self::ROLE_HOD; }
    public function isMatron() { return $this->role === self::ROLE_MATRON; }
    public function isDoctor() { return $this->role === self::ROLE_DOCTOR; }
    public function isNurse() { return $this->role === self::ROLE_NURSE; }
    public function isPatient() { return $this->role === self::ROLE_PATIENT; }
    public function isDonor() { return $this->role === self::ROLE_DONOR; }
    
    // Helper for Pharmacy Middleware
    public function isPharmacist() {
        return in_array($this->role, [
            self::ROLE_PHARMACIST_PRIMARY,
            self::ROLE_PHARMACIST_SENIOR,
            self::ROLE_PHARMACIST_CLINIC
        ]);
    }

    /**
     * Check if the user has a specific role.
     * Supports: 'admin', 'admin|doctor', or ['admin', 'doctor']
     * * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles): bool
    {
        if (is_string($roles)) {
            // Handle pipe-separated strings like "admin|doctor"
            if (str_contains($roles, '|')) {
                $roles = explode('|', $roles);
            } else {
                return $this->role === $roles;
            }
        }

        if (is_array($roles)) {
            return in_array($this->role, $roles);
        }

        return false;
    }

    // --- RELATIONSHIPS ---

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

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patient_id');
    }

    public function medicalHistories()
    {
        return $this->hasMany(MedicalHistory::class, 'patient_id');
    }

    // EMR relationships (Through Appointments)
    public function vitals()
    {
        return $this->hasManyThrough(Vitals::class, Appointment::class, 'patient_id', 'appointment_id');
    }

    public function clinicalNotes()
    {
        return $this->hasManyThrough(ClinicalNote::class, Appointment::class, 'patient_id', 'appointment_id');
    }

    public function medications()
    {
        return $this->hasManyThrough(Medication::class, Appointment::class, 'patient_id', 'appointment_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function doctorProfile()
    {
        return $this->hasOne(Doctor::class);
    }

    public function pharmacyOrders()
    {
        return $this->hasMany(PharmacyOrder::class, 'ordered_by');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function sentInvitations()
    {
        return $this->hasMany(Invitation::class, 'invited_by');
    }

    public function receivedInvitations()
    {
        return $this->hasMany(Invitation::class, 'email');
    }
}