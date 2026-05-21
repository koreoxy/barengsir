<?php

namespace App\Traits;

use App\Jobs\LogAuditTrailJob;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            $model->logAudit('created', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $old = array_intersect_key($model->getRawOriginal(), $model->getDirty());
            $new = $model->getDirty();
            $model->logAudit('updated', $old, $new);
        });

        static::deleted(function ($model) {
            $model->logAudit('deleted', $model->getAttributes(), null);
        });
    }

    protected function logAudit(string $event, ?array $old, ?array $new): void
    {
        // Prevent logging of hidden/sensitive attributes
        $hidden = $this->getHidden();
        if ($old) $old = array_diff_key($old, array_flip($hidden));
        if ($new) $new = array_diff_key($new, array_flip($hidden));

        // Skip if nothing actually changed (e.g., updated event fired without data mutations)
        if ($event === 'updated' && empty($new)) {
            return;
        }

        $userId = Auth::id();
        $ipAddress = request()->ip() ?? '127.0.0.1';
        $userAgent = request()->userAgent() ?? 'CLI/System';

        LogAuditTrailJob::dispatch([
            'user_id' => $userId,
            'auditable_type' => get_class($this),
            'auditable_id' => $this->getKey(),
            'event' => $event,
            'old_values' => $old,
            'new_values' => $new,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }
}
