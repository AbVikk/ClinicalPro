<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DrugMg extends Model
{
    use HasFactory;

    protected $table = 'drug_mg';

    protected $fillable = [
        'mg_value',
    ];

    // Relationships
    public function drugs()
    {
        return $this->hasMany(Drug::class, 'strength_mg', 'mg_value');
    }
}