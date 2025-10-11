<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AppointmentReason;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_time',
        'type',
        'status',
        'notes',
        'reason',
        'appointment_reason_id',
    ];

    protected $casts = [
        'appointment_time' => 'datetime',
    ];

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function appointmentReason()
    {
        return $this->belongsTo(AppointmentReason::class);
    }
}