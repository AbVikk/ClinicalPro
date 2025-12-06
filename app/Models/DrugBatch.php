<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class DrugBatch extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'batch_uuid',
        'drug_id',
        'supplier_id',
        'received_quantity',
        'expiry_date',
        'cost_price',
    ];

    /**
     * The attributes that should be cast.
     * THIS FIXES THE ERROR.
     */
    protected $casts = [
        'expiry_date' => 'date',        // <--- Converts string to Carbon date
        'received_quantity' => 'integer',
        'cost_price' => 'decimal:2',
        'created_at' => 'datetime',     // <--- Ensures created_at is always a Carbon object
        'updated_at' => 'datetime',
    ];

    // --- Relationships ---

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function clinicInventories()
    {
        return $this->hasMany(ClinicInventory::class, 'batch_id');
    }

    public function stockTransfers()
    {
        return $this->hasMany(StockTransfer::class, 'batch_id');
    }
}