<?php

namespace FinTrack\FinLib\Events;

use FinTrack\Core\Events\ModelUpdated;
use FinTrack\FinLib\Models\Expense;

class ExpenseUpdated extends ModelUpdated
{
    public function __construct(Expense $model)
    {
        parent::__construct($model);
    }
}
