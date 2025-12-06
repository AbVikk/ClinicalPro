<?php

namespace App\Traits;

use App\Scopes\HospitalScope;
use App\Models\Hospital;
use Illuminate\Support\Facades\Auth;

trait BelongsToHospital
{
    /**
     * The "booting" method of the model.
     */
    protected static function bootBelongsToHospital()
    {
        // 1. Add the Global Scope (Filter queries)
        static::addGlobalScope(new HospitalScope);

        // 2. Auto-save hospital_id when creating new records
        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->hospital_id) {
                $model->hospital_id = Auth::user()->hospital_id;
            }
        });
    }

    /**
     * Relationship to the Hospital model
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}