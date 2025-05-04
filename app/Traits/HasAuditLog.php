<?php

namespace App\Traits;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait HasAuditLog
{
    public static function bootHasAuditLog()
    {
        // Default "updating" behavior for when a record is updated
        static::updating(function ($model) {
            $changes = $model->getDirty();

            if (!empty($changes)) {
                AuditLog::create([
                    'auditlogable_type' => get_class($model),
                    'auditlogable_id'   => $model->getKey(),
                    'user_id'           => Auth::id(),
                    'event'             => 'updated',
                    'old_values'        => json_encode($model->getOriginal()),
                    'new_values'        => json_encode($changes),
                    'ip_address'        => request()->ip(),
                    'comments'          => null, // Default if no custom comment is provided
                ]);
            }
        });

        // Default "created" behavior when a record is created
        static::created(function ($model) {
            AuditLog::create([
                'auditlogable_type' => get_class($model),
                'auditlogable_id'   => $model->getKey(),
                'user_id'           => Auth::id(),
                'event'             => 'created',
                'new_values'        => json_encode($model->getAttributes()),
                'ip_address'        => request()->ip(),
            ]);
        });

        // Default "deleted" behavior when a record is deleted
        static::deleted(function ($model) {
            AuditLog::create([
                'auditlogable_type' => get_class($model),
                'auditlogable_id'   => $model->getKey(),
                'user_id'           => Auth::id(),
                'event'             => 'deleted',
                'old_values'        => json_encode($model->getOriginal()),
                'ip_address'        => request()->ip(),
            ]);
        });
    }

    // Custom method to log custom events like status updates or any custom changes
    public static function logCustomEvent($model, $event, $customComment = null, $additionalData = [])
    {
        AuditLog::create([
            'auditlogable_type' => get_class($model),
            'auditlogable_id'   => $model->getKey(),
            'user_id'           => Auth::id(),
            'event'             => $event, // Custom event like 'status_updated', etc.
            'old_values'        => isset($additionalData['old_values']) ? json_encode($additionalData['old_values']) : null,
            'new_values'        => isset($additionalData['new_values']) ? json_encode($additionalData['new_values']) : null,
            'ip_address'        => request()->ip(),
            'comments'          => $customComment, // Custom comment passed in the method
        ]);
    }
}
