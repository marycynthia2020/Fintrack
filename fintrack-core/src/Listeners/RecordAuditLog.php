<?php

namespace FinTrack\Core\Listeners;

use FinTrack\Core\Events\ModelCreated;
use FinTrack\Core\Events\ModelDeleted;
use FinTrack\Core\Events\ModelUpdated;
use FinTrack\FinLib\Models\AuditLog;

class RecordAuditLog
{
    public function handle(ModelCreated|ModelUpdated|ModelDeleted $event): void
    {
        $model = $event->model;

        $eventType = match (true) {
            $event instanceof ModelCreated => 'created',
            $event instanceof ModelUpdated => 'updated',
            $event instanceof ModelDeleted => 'deleted',
        };

        $metadata = $event instanceof ModelUpdated
            ? ['model' => $model::class, 'model_id' => $model->id, 'original' => $event->original, 'changes' => $event->changes]
            : ['model' => $model::class, 'model_id' => $model->id, 'attributes' => $model->getAttributes()];

        AuditLog::create([
            'organization_id' => $model->organization_id,
            'event_type' => $eventType,
            'metadata' => $metadata,
        ]);
    }
}
