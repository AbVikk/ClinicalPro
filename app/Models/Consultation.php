<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consultation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'location_id',
        'delivery_channel',
        'service_type',
        'reason',
        'duration_minutes',
        'fee',
        'status',
        'start_time',
        'end_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fee' => 'decimal:2',
        'duration_minutes' => 'integer',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the patient for this consultation.
     */
    public function patient()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the doctor for this consultation.
     */
    public function doctor()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the clinic where this consultation took place.
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'location_id');
    }

    /**
     * Get the payment for this consultation.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}