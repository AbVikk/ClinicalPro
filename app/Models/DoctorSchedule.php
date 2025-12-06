<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;
use App\Traits\BelongsToHospital;

class DoctorSchedule extends Model
{
    use HasFactory, Auditable, BelongsToHospital;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hospital_id',
        'doctor_id',
        'location',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'recurrence',
        'day_of_week',
        'session_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
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
        return $this->belongsTo(Clinic::class, 'location', 'id');
    }
}