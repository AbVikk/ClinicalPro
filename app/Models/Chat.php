<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToHospital;

class Chat extends Model
{
    use BelongsToHospital;

    protected $fillable = [
        'hospital_id',
        'doctor_id',
        'patient_id',
        'message',
        'sender_type',
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