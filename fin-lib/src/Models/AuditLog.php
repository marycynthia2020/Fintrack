<?php

namespace FinTrack\FinLib\Models;

use FinTrack\Core\Models\BaseModel;
use FinTrack\FinLib\Events\AuditLogCreated;

class AuditLog extends BaseModel
{
    protected $fillable = [
        'organization_id',
        'event_type',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $dispatchesEvents = [
        'created' => AuditLogCreated::class,
    ];
}