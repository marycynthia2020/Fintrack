<?php

namespace FinTrack\FinLib\Events;

use FinTrack\Core\Events\ModelCreated;

class AccountCreated extends ModelCreated
{
    public function __construct(\Illuminate\Database\Eloquent\Model $model)
    {
        $debounce = $this->debounce();
        parent::__construct($model);
    }
}
