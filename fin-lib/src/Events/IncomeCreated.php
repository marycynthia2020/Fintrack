<?php

namespace FinTrack\FinLib\Events;

use FinTrack\Core\Events\ModelCreated;
use Fintrack\FinLib\Models\Income;

class IncomeCreated extends ModelCreated
{
    public function __construct(Income $model)
    {
        
    }
}
