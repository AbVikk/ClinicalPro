<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorSchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'doctor_id',
        'location',
        'start_date',     // <-- This is new
        'end_date',       // <-- This is new
        'start_time',
        'end_time',
        'recurrence',
        'day_of_week',
        'session_type',
        // We removed 'schedule_date' because it's gone
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date', // This tells Laravel to treat it as a date
        'end_date' => 'date',   // This tells Laravel to treat it as a date
    ];

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the clinic (if physical) for this schedule.
     */
    public function clinic()
    {
        // This links the 'location' column on this table
        // to the 'id' column on the 'clinics' table.
        return $this->belongsTo(Clinic::class, 'location', 'id');
    }
}