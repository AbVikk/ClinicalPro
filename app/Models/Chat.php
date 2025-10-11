<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'message',
        'sender_type', // e.g., 'doctor', 'patient'
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}