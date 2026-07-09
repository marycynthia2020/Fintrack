<?php

namespace FinTrack\FinLib\Events;

use FinTrack\Core\Events\ModelCreated;
use FinTrack\FinLib\Models\Income;

class IncomeCreated extends ModelCreated
{
    public function __construct(Income $model)
    {
        parent::__construct($model);
    }
}
