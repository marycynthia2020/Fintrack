<?php

namespace FinTrack\FinLib\Models;

use FinTrack\Core\Models\BaseModel;
use FinTrack\Core\Models\Organization;
use FinTrack\Core\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends BaseModel
{
    protected $fillable = [
        'organization_id',
        'event_type',
        'metadata',
    ];
}