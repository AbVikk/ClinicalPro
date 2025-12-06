<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class HospitalScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     * * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        // 1. Check if user is logged in
        if (Auth::check()) {
            $user = Auth::user();

            // 2. If user belongs to a hospital (Regular User)
            // We check isset() to be safe, ensuring the user object actually has this property loaded
            if (isset($user->hospital_id) && $user->hospital_id) {
                
                // 3. Apply the filter
                // We use $model->getTable() to qualify the column (e.g., "drugs.hospital_id")
                // This prevents "Ambiguous column" errors when doing Joins.
                $builder->where($model->getTable() . '.hospital_id', $user->hospital_id);
            }
            
            // 4. If user is Super Admin (hospital_id is null), they see EVERYTHING.
            // (No 'where' clause added)
        }
    }
}