<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            self::logActivity($model, 'create');
        });

        static::updated(function (Model $model) {
            self::logActivity($model, 'update');
        });

        static::deleted(function (Model $model) {
            self::logActivity($model, 'delete');
        });
    }

    protected static function logActivity(Model $model, string $action)
    {
        $oldValues = null;
        $newValues = null;

        if ($action === 'update') {
            $oldValues = array_intersect_key($model->getOriginal(), $model->getDirty());
            $newValues = $model->getDirty();
            
            // If no actual changes (e.g. only timestamps or hidden fields if any), return
            if (empty($newValues)) {
                return;
            }
        } elseif ($action === 'create') {
            $newValues = $model->toArray();
        } elseif ($action === 'delete') {
            $oldValues = $model->toArray();
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => class_basename($model),
            'module_id' => $model->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
