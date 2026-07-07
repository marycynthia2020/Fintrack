<?php

namespace FinTrack\FinLib\Models;

use FinTrack\Core\Models\BaseModel;
use FinTrack\Core\Models\User;
use FinTrack\FinLib\Events\ExpenseCreated;
use FinTrack\FinLib\Events\ExpenseDeleted;
use FinTrack\FinLib\Events\ExpenseUpdated;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends BaseModel
{
    protected $fillable = [
        'organization_id',
        'amount',
        'description',
        'type',
        'created_by',
        'updated_by',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    protected $dispatchesEvents = [
        'created' => ExpenseCreated::class,
        'updated' => ExpenseUpdated::class,
        'deleted' => ExpenseDeleted::class,
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}