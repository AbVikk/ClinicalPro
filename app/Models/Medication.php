<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;
use App\Traits\BelongsToHospital;

class Medication extends Model
{
    use HasFactory, Auditable, BelongsToHospital;

    protected $fillable = [
        'appointment_id',
        'hosptital_id',
        'medication_name',
        'type',
        'dosage',
        'duration',
        'use_pattern',
        'instructions',
    ];

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}