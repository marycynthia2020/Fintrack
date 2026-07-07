<?php

namespace FinTrack\Core\Events;

use Illuminate\Database\Eloquent\Model;

abstract class ModelUpdated extends ModelEvent
{
    /** @var array<string, mixed> Values as they were before the update, keyed by changed attribute. */
    public readonly array $original;

    /** @var array<string, mixed> Values as they are after the update, keyed by changed attribute. */
    public readonly array $changes;

    public function __construct(Model $model)
    {
        parent::__construct($model);

        $this->changes = $model->getChanges();
        $this->original = array_intersect_key($model->getOriginal(), $this->changes);
    }
}
