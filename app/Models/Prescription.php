<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'consultation_id',
        'status',
        'notes',
        'refills_allowed',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function pharmacyOrder()
    {
        return $this->hasOne(PharmacyOrder::class);
    }
}