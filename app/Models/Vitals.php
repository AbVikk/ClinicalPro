<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;
use App\Traits\BelongsToHospital;

class Vitals extends Model
{
    use HasFactory, Auditable, BelongsToHospital;

    protected $fillable = [
        'hospital_id',
        'appointment_id',
        'doctor_id',
        'recorded_at',
        'blood_pressure',
        'temperature',
        'pulse',
        'respiratory_rate',
        'spo2',
        'height',
        'weight',
        'waist',
        'bsa',
        'bmi',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}