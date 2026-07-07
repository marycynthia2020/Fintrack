<?php

namespace FinTrack\FinLib\Models;

use FinTrack\Core\Models\BaseModel;
use FinTrack\FinLib\Events\AccountCreated;
use FinTrack\FinLib\Events\AccountDeleted;
use FinTrack\FinLib\Events\AccountUpdated;

class Account extends BaseModel
{
    protected $fillable = [
        'organization_id',
        'balance',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $dispatchesEvents = [
        'created' => AccountCreated::class,
        'updated' => AccountUpdated::class,
        'deleted' => AccountDeleted::class,
    ];
}