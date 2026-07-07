<?php

namespace FinTrack\FinLib\Services;

use FinTrack\FinLib\Events\ExpenseCreated;
use FinTrack\FinLib\Events\ExpenseDeleted;
use FinTrack\FinLib\Events\ExpenseUpdated;
use FinTrack\FinLib\Models\Expense;

class ExpenseService
{
    public function create(array $data): Expense
    {
        $expense = Expense::create($data);

        ExpenseCreated::dispatch($expense);

        return $expense;
    }

    public function update(Expense $expense, array $data): Expense
    {
        $expense->update($data);

        ExpenseUpdated::dispatch($expense);

        return $expense;
    }

    public function delete(Expense $expense): bool
    {
        $deleted = (bool) $expense->delete();

        ExpenseDeleted::dispatch($expense);

        return $deleted;
    }

    public function list(array $filters = [])
    {
        return Expense::query()
            ->when($filters['organization_id'] ?? null, fn ($query, $organizationId) => $query->where('organization_id', $organizationId))
            ->get();
    }

    public function find(string $id): ?Expense
    {
        return Expense::find($id);
    }
}
