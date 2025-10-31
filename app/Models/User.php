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

    // Accessor for age_gender
    public function getAgeGenderAttribute()
    {
        if ($this->date_of_birth && $this->gender) {
            $age = Carbon::parse($this->date_of_birth)->age;
            return $age . ' / ' . ucfirst($this->gender);
        } elseif ($this->date_of_birth) {
            $age = Carbon::parse($this->date_of_birth)->age;
            return $age . ' / N/A';
        } elseif ($this->gender) {
            return 'N/A / ' . ucfirst($this->gender);
        }
        return 'N/A / N/A';
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

    // Medical history relationship
    public function medicalHistories()
    {
        // For patients, this links to their medical history
        return $this->hasMany(MedicalHistory::class, 'patient_id');
    }

    // EMR relationships for patient data across appointments
    public function vitals()
    {
        // Vitals through appointments
        return $this->hasManyThrough(Vitals::class, Appointment::class, 'patient_id', 'appointment_id');
    }

    public function clinicalNotes()
    {
        // Clinical notes through appointments
        return $this->hasManyThrough(ClinicalNote::class, Appointment::class, 'patient_id', 'appointment_id');
    }

    public function medications()
    {
        // Medications through appointments
        return $this->hasManyThrough(Medication::class, Appointment::class, 'patient_id', 'appointment_id');
    }

    // New relationships for the updated database structure
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

    // Doctor-specific relationships
    public function doctorProfile()
    {
        return $this->hasOne(Doctor::class);
    }

    // Pharmacy relationships
    public function pharmacyOrders()
    {
        return $this->hasMany(PharmacyOrder::class, 'ordered_by');
    }

    // Attendance relationships
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Leave request relationships
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    // Invitation relationships
    public function sentInvitations()
    {
        return $this->hasMany(Invitation::class, 'invited_by');
    }

    public function receivedInvitations()
    {
        return $this->hasMany(Invitation::class, 'email');
    }
}