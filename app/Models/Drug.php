<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Drug extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'strength_mg',
        'unit_price',
        'is_controlled',
        'details',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'is_controlled' => 'boolean',
        'details' => 'array',
    ];

    // Relationships
    public function batches()
    {
        return $this->hasMany(DrugBatch::class, 'drug_id');
    }

    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class, 'drug_id');
    }

    public function clinicInventories()
    {
        return $this->hasManyThrough(ClinicInventory::class, DrugBatch::class);
    }
    
    // Relationship to DrugCategory
    public function category()
    {
        return $this->belongsTo(DrugCategory::class, 'category', 'name');
    }
    
    // Relationship to DrugMg
    public function mg()
    {
        return $this->belongsTo(DrugMg::class, 'strength_mg', 'mg_value');
    }
    
    // Accessor for details
    public function getDetailsAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }
    
    // Mutator for details
    public function setDetailsAttribute($value)
    {
        $this->attributes['details'] = is_array($value) ? json_encode($value) : $value;
    }
}