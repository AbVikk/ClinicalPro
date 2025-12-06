<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;
use App\Traits\BelongsToHospital;

class Disbursement extends Model
{
    use HasFactory, Auditable, BelongsToHospital;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hospital_id',
        'recipient_id',
        'clinic_id',
        'amount',
        'type',
        'status',
        'disbursement_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'disbursement_date' => 'datetime',
    ];

    /**
     * Get the recipient of this disbursement.
     */
    public function recipient()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the clinic associated with this disbursement.
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
