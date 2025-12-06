<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToHospital;

class Category extends Model
{
    use HasFactory, BelongsToHospital;
    
    protected $fillable = [
        'hospital_id',
        'name',
        'description',
    ];
    
    // Relationships
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}