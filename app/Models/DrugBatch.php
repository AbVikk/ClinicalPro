<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DrugBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_uuid',
        'drug_id',
        'supplier_id',
        'received_quantity',
        'expiry_date',
    ];

    protected $casts = [
        'received_quantity' => 'integer',
        'expiry_date' => 'date',
    ];

    // Relationships
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