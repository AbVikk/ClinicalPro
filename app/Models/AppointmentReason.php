<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToHospital;

class AppointmentReason extends Model
{
    use HasFactory, BelongsToHospital;

    protected $fillable = [
        'hospital_id',
        'name',
        'description',
        'is_active',
    ];

    // Relationships
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}