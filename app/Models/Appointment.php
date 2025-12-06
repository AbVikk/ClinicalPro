<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AppointmentReason;
use App\Traits\Auditable;
use App\Traits\BelongsToHospital;

class Appointment extends Model
{
    use HasFactory, Auditable, BelongsToHospital;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'hospital_id',
        'appointment_time',
        'type',
        'status',
        'notes',
        'reason',
        'appointment_reason_id',
        'consultation_id',
        'payment_id',
        'started_at',
        'completed_at',
        'end_reason',
    ];

    protected $casts = [
        'appointment_time' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Accessor for appointment_date to maintain backward compatibility
     */
    public function getAppointmentDateAttribute()
    {
        return $this->appointment_time;
    }

    // ... (All other relationships are fine) ...
    
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

    public function appointmentDetail()
    {
        return $this->hasOne(AppointmentDetail::class);
    }

    public function vitals()
    {
        return $this->hasOne(Vitals::class);
    }

    public function clinicalNote()
    {
        return $this->hasOne(ClinicalNote::class);
    }

    public function medications()
    {
        return $this->hasMany(Medication::class);
    }
    
    public function labTests()
    {
        return $this->hasMany(LabTest::class);
    }
    
    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}