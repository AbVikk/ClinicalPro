<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'appointment_id',
        'consultation_id',
        'clinic_id',
        'order_id',
        'amount',
        'method',
        'status',
        'reference',
        'transaction_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    // Define the allowed methods
    public const METHODS = [
        'card_online',
        'cash_in_clinic',
        'bank_transfer',
        'paystack'
    ];

    // Define the allowed statuses
    public const STATUSES = [
        'paid',
        'failed',
        'refunded',
        'pending_cash_verification',
        'pending',
        'completed'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function order()
    {
        return $this->belongsTo(PharmacyOrder::class, 'order_id');
    }
}