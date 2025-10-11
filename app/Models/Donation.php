<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'patient_id',
        'amount',
        'payment_method',
        'status', // e.g., 'pending', 'completed'
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