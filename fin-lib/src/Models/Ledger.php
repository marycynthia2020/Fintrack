<?php

namespace FinTrack\FinLib\Models;

use FinTrack\Core\Models\BaseModel;
use FinTrack\Core\Models\User;
use FinTrack\FinLib\Events\LedgerCreated;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ledger extends BaseModel
{
    protected $fillable = [
        'organization_id',
        'amount',
        'ledgerable_type',
        'ledgerable_id',
        'type',
        'description',
        'event_type',  
        'created_by',
        'processed_at',
        'metadata',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected $dispatchesEvents = [
        'created' => LedgerCreated::class,
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}