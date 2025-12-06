<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model; // <--- Added Import

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            self::logAudit('Created', $model);
        });

        static::updated(function ($model) {
            if ($model->isDirty()) {
                self::logAudit('Updated', $model, $model->getChanges());
            }
        });

        static::deleted(function ($model) {
            self::logAudit('Deleted', $model);
        });
    }

    // FIX: Added 'Model' type hint to satisfy Intelephense
    protected static function logAudit(string $action, Model $model, $details = null)
    {
        if (Auth::check()) {
            AuditLog::create([
                'user_id'    => Auth::id(),
                'action'     => $action,
                'model_type' => get_class($model),
                'model_id'   => $model->getKey(), // getKey() is safer than ->id
                'details'    => $details ? json_encode($details) : null,
                'ip_address' => request()->ip(),
            ]);
        }
    }
}