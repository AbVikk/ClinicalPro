<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;
use App\Traits\BelongsToHospital;

class MedicalHistory extends Model
{
    use HasFactory, Auditable, BelongsToHospital;

    protected $fillable = [
        'patient_id',
        'hospital_id',
        'condition_name',
        'diagnosis_date',
        'description',
    ];

    protected $casts = [
        'diagnosis_date' => 'date',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}