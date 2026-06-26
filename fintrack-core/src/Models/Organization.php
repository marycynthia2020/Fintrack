<?php

namespace FinTrack\Core\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use FinTrack\Core\Traits\HasArtifacts;
use FinTrack\Core\Traits\HasAttachments;

class Organization extends Model
{
    use SoftDeletes, HasUuids, HasArtifacts, HasAttachments;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'organization_id', 'id');
    }
}
