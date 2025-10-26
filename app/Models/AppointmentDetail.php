<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppointmentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'blood_group',
        'complaints',
        'diagnosis',
        'advice',
        'follow_up_date',
        'follow_up_time',
    ];

    protected $casts = [
        'complaints' => 'array',
        'diagnosis' => 'array',
        'follow_up_date' => 'date',
        'follow_up_time' => 'datetime',
    ];

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function vitals()
    {
        // Fix: Use the correct foreign key
        return $this->hasOne(Vitals::class, 'appointment_id', 'appointment_id');
    }

    public function clinicalNote()
    {
        // Fix: Use the correct foreign key
        return $this->hasOne(ClinicalNote::class, 'appointment_id', 'appointment_id');
    }

    public function medications()
    {
        // Fix: Use the correct foreign key
        return $this->hasMany(Medication::class, 'appointment_id', 'appointment_id');
    }
}