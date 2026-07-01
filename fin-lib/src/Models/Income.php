<?php

namespace FinTrack\FinLib\Models;

use FinTrack\Core\Models\BaseModel;
use FinTrack\Core\Models\User;
use  Illuminate\Database\Eloquent\Relations\BelongsTo;  


class Income extends BaseModel 
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
    
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
