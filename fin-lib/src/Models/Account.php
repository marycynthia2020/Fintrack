<?php

namespace FinTrack\FinLib\Models;

use FinTrack\Core\Models\BaseModel;

class Account extends BaseModel
{
    protected $fillable = [
        'organization_id',
        'balance',
        'metadata',
    ];


}