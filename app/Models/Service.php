<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $service_name
 * @property string $service_type
 * @property float $price_amount
 * @property string $price_currency
 * @property bool $is_active
 */
class Service extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'hospital_services';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'service_name',
        'service_type',
        'description',
        'price_amount',
        'price_currency',
        'is_active',
        'default_duration',
        'time_pricing',
    ];

    /**
     * The attributes that should be cast to native types.
     * Price amount is cast to float for immediate mathematical use.
     * @var array
     */
    protected $casts = [
        'price_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'time_pricing' => 'array',
        'default_duration' => 'integer',
    ];

    /**
     * Global Scope: Only fetch active services by default.
     * This ensures disabled services don't show up in booking forms.
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('active', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->where('is_active', true);
        });
    }
    
    /**
     * Accessor to format the price for display (e.g., NGN 5,000.00).
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        // Assuming NGN is the main currency. Adjust formatting as needed.
        return $this->price_currency . ' ' . number_format($this->price_amount, 2);
    }
    
    /**
     * Get the time-based pricing for this service.
     */
    public function timePricings()
    {
        return $this->hasMany(ServiceTimePricing::class, 'service_id');
    }
    
    /**
     * Get active time-based pricing for this service.
     */
    public function activeTimePricings()
    {
        return $this->hasMany(ServiceTimePricing::class, 'service_id')->where('is_active', true);
    }
}