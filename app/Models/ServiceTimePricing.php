<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToHospital;

class ServiceTimePricing extends Model
{
    use HasFactory, BelongsToHospital;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'service_time_pricing';

    protected $fillable = [
        'hospital_id',
        'service_id',
        'duration_minutes',
        'price',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the service that owns this pricing.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}