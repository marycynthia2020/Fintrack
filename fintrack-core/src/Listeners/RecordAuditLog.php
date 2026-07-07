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

        AuditLog::create([
            'organization_id' => $model->organization_id,
            'event_type' => $eventType,
            'metadata' => [
                'model' => $model::class,
                'model_id' => $model->id,
                'attributes' => $model->getAttributes(),
            ],
        ]);
    }
}
