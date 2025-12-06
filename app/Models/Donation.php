<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use App\Traits\BelongsToHospital;

class Donation extends Model
{
    use Auditable, BelongsToHospital;

    protected $fillable = [
        'hospital_id',
        'patient_id',
        'amount',
        'payment_method',
        'status',
        'donated_at',
    ];

    protected $casts = [
        'donated_at' => 'datetime',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}