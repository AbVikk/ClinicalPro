<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;
use App\Traits\BelongsToHospital;

class InventoryPrediction extends Model
{
    use HasFactory, Auditable, BelongsToHospital;

    protected $fillable = [
        'drug_name',
        'hospital_id',
        'month',
        'year',
        'predicted_quantity',
        'reorder_point',
        'recommended_order_quantity',
        'risk_factors',
        'notes',
        'generated_at',
    ];

    protected $casts = [
        'predicted_quantity' => 'integer',
        'reorder_point' => 'integer',
        'recommended_order_quantity' => 'integer',
        'risk_factors' => 'array',
        'generated_at' => 'datetime',
    ];

    // Relationships
    public function drug()
    {
        return $this->belongsTo(Drug::class, 'drug_name', 'name');
    }
}