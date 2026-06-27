<?php

namespace FinTrack\Core\Models;

use FinTrack\Core\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attachment extends BaseModel
{

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organization_id',
        'name',
        'original_name',
        'path',
        'url',
        'mime_type',
        'size',
        'disk',
        'metadata',
    ];

    protected $appends = ['full_url'];

    protected $casts = [
        'metadata'   => 'array',
        'size'       => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function getFullUrlAttribute(): string
    {
        $fs = \Illuminate\Support\Facades\Storage::disk($this->disk);
        assert($fs instanceof \Illuminate\Filesystem\FilesystemAdapter);
        return $fs->url($this->path);
    }

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
