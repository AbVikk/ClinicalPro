<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'doctor_id',  // Added this field
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