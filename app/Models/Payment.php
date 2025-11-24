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
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime',
        'metadata' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | ENUMS: The Rulebook
    |--------------------------------------------------------------------------
    | We define these here so we never make a typo in the database.
    */

    // Payment Methods
    public const METHOD_CARD_ONLINE = 'card_online';
    public const METHOD_CASH = 'cash_in_clinic';
    public const METHOD_BANK_TRANSFER = 'bank_transfer';
    // 'paystack' is technically the gateway, but if you use it as a method name in DB:
    public const METHOD_PAYSTACK = 'paystack'; 

    // Payment Statuses
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUNDED = 'refunded';
    public const STATUS_PENDING_VERIFICATION = 'pending_cash_verification';
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';

    /**
     * Get the valid payment methods as a list.
     */
    public static function getMethods(): array
    {
        return [
            self::METHOD_CARD_ONLINE,
            self::METHOD_CASH,
            self::METHOD_BANK_TRANSFER,
            self::METHOD_PAYSTACK,
        ];
    }

    /**
     * Get the valid statuses as a list.
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PAID,
            self::STATUS_FAILED,
            self::STATUS_REFUNDED,
            self::STATUS_PENDING_VERIFICATION,
            self::STATUS_PENDING,
            self::STATUS_COMPLETED,
        ];
    }

    // --- Relationships ---

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