<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'user_id', // Foreign key to users table
        'phone',
        'medical_history',
    ];

    // Explicitly define the route key name
    public function getRouteKeyName()
    {
        return 'id';
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function medicalHistories()
    {
        return $this->hasMany(MedicalHistory::class);
    }

    // EMR relationships
    public function vitals()
    {
        // Vitals through appointments
        return $this->hasManyThrough(Vitals::class, Appointment::class, 'patient_id', 'appointment_id');
    }

    public function clinicalNotes()
    {
        // Clinical notes through appointments
        return $this->hasManyThrough(ClinicalNote::class, Appointment::class, 'patient_id', 'appointment_id');
    }

    public function medications()
    {
        // Medications through appointments
        return $this->hasManyThrough(Medication::class, Appointment::class, 'patient_id', 'appointment_id');
    }
}