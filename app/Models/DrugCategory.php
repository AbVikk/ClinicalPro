<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class DrugCategory extends Model
{
    use HasFactory, Auditable;

    protected $table = 'drug_categories';

    protected $fillable = [
        'name',
        'description',
    ];

    // Relationships
    public function drugs()
    {
        return $this->hasMany(Drug::class, 'category', 'name');
    }
}