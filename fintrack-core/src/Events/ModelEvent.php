<?php

namespace FinTrack\Core\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;

abstract class ModelEvent
{
    use Dispatchable;

    public function __construct(public readonly Model $model)
    {
    }
}
