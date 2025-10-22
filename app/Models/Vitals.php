<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vitals extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'recorded_at',
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
}