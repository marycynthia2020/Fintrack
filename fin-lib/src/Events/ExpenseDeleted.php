<?php

namespace FinTrack\FinLib\Events;

use FinTrack\Core\Events\ModelDeleted;
use FinTrack\FinLib\Models\Expense;

class ExpenseDeleted extends ModelDeleted
{
    public function __construct(Expense $model)
    {
        parent::__construct($model);
    }
}
