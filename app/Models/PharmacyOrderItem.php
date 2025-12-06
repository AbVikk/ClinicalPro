<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PharmacyOrderItem extends Model
{
    use HasFactory;

    // Specify the correct table name
    protected $table = 'pharmacy_order_items';

    protected $fillable = [
        'pharmacy_order_id',
        'drug_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function pharmacyOrder()
    {
        return $this->belongsTo(PharmacyOrder::class);
    }

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }
}