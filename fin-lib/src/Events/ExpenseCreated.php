<?php

namespace FinTrack\FinLib\Events;

use FinTrack\Core\Events\ModelCreated;
use FinTrack\FinLib\Models\Expense;

class ExpenseCreated extends ModelCreated
{
    public function __construct(Expense $model)
    {
        parent::__construct($model);
    }
}
