<?php

namespace FinTrack\Core\Models;

use FinTrack\Core\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Artifact extends BaseModel
{
    

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organization_id',
        'type',
        'name',
        'description',
        'path',
        'url',
        'mime_type',
        'size',
        'version',
        'status',
        'metadata',
    ];

    protected $casts = [
        'metadata'   => 'array',
        'size'       => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function artifactable(): MorphTo
    {
        return $this->morphTo();
    }
}
