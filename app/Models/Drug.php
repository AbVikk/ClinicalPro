<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;
use App\Traits\BelongsToHospital;

class Drug extends Model
{
    use HasFactory, Auditable, BelongsToHospital;

    protected $fillable = [
        'name',
        'category',
        'strength_mg',
        'unit_price',
        'is_controlled',
        'details',
        'hospital_id',
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
        return $this->hasManyThrough(ClinicInventory::class, DrugBatch::class, 'drug_id', 'batch_id', 'id', 'id');
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