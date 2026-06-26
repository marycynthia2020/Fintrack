<?php

namespace FinTrack\Core\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use FinTrack\Core\Traits\HasOrganization;

class BaseModel extends Model
{
    use SoftDeletes, HasUuids, HasOrganization;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'organization_id',
    ];
}