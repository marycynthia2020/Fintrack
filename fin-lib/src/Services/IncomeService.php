<?php

namespace FinTrack\FinLib\Services;

use FinTrack\FinLib\Events\IncomeCreated;
use FinTrack\FinLib\Events\IncomeDeleted;
use FinTrack\FinLib\Events\IncomeUpdated;
use FinTrack\FinLib\Models\Income;

class IncomeService
{
    public function create(array $data): Income
    {
        $income = Income::create($data);

        IncomeCreated::dispatch($income);

        return $income;
    }

    public function update(Income $income, array $data): Income
    {
        $income->update($data);

        IncomeUpdated::dispatch($income);

        return $income;
    }

    public function delete(Income $income): bool
    {
        $deleted = (bool) $income->delete();

        IncomeDeleted::dispatch($income);

        return $deleted;
    }

    public function list(array $filters = [])
    {
        return Income::query()
            ->when($filters['organization_id'] ?? null, fn ($query, $organizationId) => $query->where('organization_id', $organizationId))
            ->get();
    }

    public function find(string $id): ?Income
    {
        return Income::find($id);
    }
}
