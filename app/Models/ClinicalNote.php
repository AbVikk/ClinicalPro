<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;
use App\Traits\BelongsToHospital;

class ClinicalNote extends Model
{
    use HasFactory, Auditable, BelongsToHospital;

    protected $fillable = [
        'hostpital_id',
        'appointment_id',
        'doctor_id',
        'note_text',
        'skin_allergy',
        'note_type',  // Added this field
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