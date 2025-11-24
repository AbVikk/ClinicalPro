<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use App\Models\Department;
use App\Models\DoctorSchedule;
use App\Models\Appointment;
use App\Models\Consultation; // Added this import
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Doctor extends Model
{
    use HasFactory;
    
    protected $table = 'doctors_new';
    
    protected $fillable = [
        'user_id',
        'doctor_id',
        'specialization',
        'department_id',
        'category_id',
        'license_number',
        'proof_of_identity',
        'status',
        'live_status',
        'medical_school',
        'residency',
        'fellowship',
        'years_of_experience',
        'bio',
    ];

    protected $casts = [
        'years_of_experience' => 'integer',
    ];

    public function getRouteKeyName()
    {
        return 'user_id';
    }

    // --- Relationships ---
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        // Links doctors_new.user_id -> appointments.doctor_id
        return $this->hasMany(Appointment::class, 'doctor_id', 'user_id'); 
    }
    
    /**
     * FIX: Added the missing consultations relationship.
     * Links doctors_new.user_id -> consultations.doctor_id
     */
    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'doctor_id', 'user_id');
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    public function schedules()
    {
        // Links doctors_new.user_id -> doctor_schedules.doctor_id
        return $this->hasMany(DoctorSchedule::class, 'doctor_id', 'user_id'); 
    }
    
    // --- AI Query Scopes ---

    public function scopeWhereIsAvailable($query, $targetDateTime)
    {
        $dayOfWeek = strtolower($targetDateTime->format('l'));
        $timeOnly = $targetDateTime->toTimeString();
        $dateOnly = $targetDateTime->toDateString();

        return $query->whereHas('schedules', function ($q) use ($dayOfWeek, $timeOnly, $dateOnly) {
            $q->where('day_of_week', $dayOfWeek)
              ->where('start_date', '<=', $dateOnly)
              ->where('end_date', '>=', $dateOnly)
              ->where('start_time', '<=', $timeOnly)
              ->where('end_time', '>=', $timeOnly);
        });
    }
    
    public function scopeWhereHasNoConflict($query, $targetDateTime, $duration)
    {
        $slotEnd = $targetDateTime->copy()->addMinutes($duration);
        
        // 1. Check Appointments Table
        $query->whereDoesntHave('appointments', function ($q) use ($targetDateTime, $slotEnd) {
            $q->where('status', '!=', 'cancelled')
              ->where('status', '!=', 'completed')
              ->where(function ($q2) use ($targetDateTime, $slotEnd) {
                  $q2->where('appointment_time', '<', $slotEnd)
                     ->whereRaw('DATE_ADD(appointment_time, INTERVAL 30 MINUTE) > ?', [$targetDateTime]);
              });
        });

        // 2. Check Consultations Table (The Logic that was crashing)
        // Now that we added the consultations() method above, this will work.
        return $query->whereDoesntHave('consultations', function ($q) use ($targetDateTime, $slotEnd) {
             $q->whereNotIn('status', ['completed', 'cancelled'])
               ->where(function ($timeQ) use ($targetDateTime, $slotEnd) {
                   $timeQ->where('start_time', '<', $slotEnd)
                         ->whereRaw('DATE_ADD(start_time, INTERVAL duration_minutes MINUTE) > ?', [$targetDateTime]);
               });
        });
    }
}