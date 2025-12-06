<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;
use App\Traits\BelongsToHospital;

class LabTest extends Model
{
    use HasFactory, Auditable, BelongsToHospital;

    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'hospital_id',
        'test_name',
        'file_path',
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