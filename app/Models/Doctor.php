<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use App\Models\Department;
use App\Models\DoctorSchedule;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Doctor extends Model
{
    use HasFactory;
    
    // --- Model Configuration ---
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
        'availability',
        'medical_school',
        'residency',
        'fellowship',
        'years_of_experience',
        'bio',
    ];

    protected $casts = [
        'availability' => 'array',
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
        // Assumes 'doctor_id' on the appointments table links to the 'id' of the doctor
        return $this->hasMany(Appointment::class, 'doctor_id'); 
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
        // Assumes 'doctor_id' on the doctor_schedules table links to the 'id' of the doctor
        return $this->hasMany(DoctorSchedule::class, 'doctor_id'); 
    }
    
    // --- AI Query Scopes ---

    /**
     * Scope to filter doctors who are generally available on a specific date and time.
     * This checks the DoctorSchedule table for working hours.
     * * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon $targetDateTime The specific date and time to check.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereIsAvailable($query, $targetDateTime)
    {
        $dayOfWeek = strtolower($targetDateTime->format('l')); // 'monday', 'tuesday', etc.
        $timeOnly = $targetDateTime->toTimeString();
        $dateOnly = $targetDateTime->toDateString();

        // 1. Check if the doctor has a schedule that includes the target time
        return $query->whereHas('schedules', function ($q) use ($dayOfWeek, $timeOnly, $dateOnly) {
            $q->where('day_of_week', $dayOfWeek)
              // Check if the date falls within the schedule's date range
              ->where('start_date', '<=', $dateOnly)
              ->where('end_date', '>=', $dateOnly)
              // Check if the time falls within the scheduled time range
              ->where('start_time', '<=', $timeOnly)
              ->where('end_time', '>=', $timeOnly);
        });
    }
    
    /**
     * Scope to exclude doctors who have a conflicting, active appointment/consultation.
     * This checks the Appointment table status and assumes the 
     * Consultation model (which holds duration) can be accessed via the Appointment model.
     * * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Carbon\Carbon $targetDateTime The proposed start time.
     * @param int $duration The length of the proposed appointment in minutes.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereHasNoConflict($query, $targetDateTime, $duration)
    {
        // Calculate the proposed END time for the new appointment slot
        $slotEnd = $targetDateTime->copy()->addMinutes($duration);
        
        // We check for any active APPOINTMENT that OVERLAPS with the proposed slot.
        return $query->whereDoesntHave('appointments', function ($q) use ($targetDateTime, $slotEnd) {
            
            // Only look at appointments that are not cancelled (assuming consultation status is checked by the controller)
            $q->where('status', '!=', 'cancelled')
              
              // CRUCIAL: Check for appointment time overlap using the standard formula.
              // This relies on the Consultation table holding duration, which we can simulate 
              // using a raw query for reliability.
              ->where(function ($q2) use ($targetDateTime, $slotEnd) {
                  // Existing appointment starts before the NEW slot ends
                  $q2->where('appointment_time', '<', $slotEnd)
                  
                  // AND Existing appointment ends AFTER the NEW slot starts
                  // (We assume a 30-minute default duration if the Consultation is missing, 
                  // but your Controller handles the actual duration check.)
                  // We use a safe join here to the Consultation table to get the true duration.
                  ->whereHas('consultation', function($q3) use ($targetDateTime) {
                      $q3->whereRaw('DATE_ADD(start_time, INTERVAL duration_minutes MINUTE) > ?', [$targetDateTime]);
                  });
              });
        });
    }
}