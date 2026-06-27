<?php

namespace FinTrack\Core\Models;

use FinTrack\Core\Traits\{
    HasArtifacts, 
    HasAttachments, 
    HasOrganization
};
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    use SoftDeletes, HasUuids, HasOrganization, HasArtifacts, HasAttachments;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organization_id',
    ];
}