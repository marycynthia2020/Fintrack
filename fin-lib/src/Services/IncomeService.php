<?php

namespace FinTrack\FinLib\Services;

use FinTrack\FinLib\Models\Account;
use FinTrack\FinLib\Models\Income;
use Illuminate\Support\Facades\DB;
use FinTrack\FinLib\Enums\IncomeType;
use Illuminate\Support\Facades\Auth;

class IncomeService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            Account::firstOrCreate(
                ['organization_id' => $data['organization_id']],
                ['balance' => 0.00]
            );

            return Income::create($data);
        });
    }

    public function update(Income $income, array $data)
    {
        return DB::transaction(function () use ($income, $data) {
            $income->update($data);
            return $income;
        });
    }

    public function delete(Income $income)
    {
        return DB::transaction(function () use ($income) {
            if (Auth::check()) {
                $income->updateQuietly(['updated_by' => Auth::id()]);
            }
            $income->delete();
            return $income;
        });
    }

    public function list(array $filters = [])
    {
        $query = Income::query();

        if (isset($filters['organization_id'])) {
            $query->where('organization_id', $filters['organization_id']);
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['created_by'])) {
            $query->where('created_by', $filters['created_by']);
        }

        if (isset($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function find(string $id)
    {
        $orgId = Auth::user()?->organization_id;

        $query = Income::query();
        if ($orgId) {
            $query->where('organization_id', $orgId);
        }

        return $query->findOrFail($id);
    }

    public function categories()
    {
        return collect(IncomeType::cases())
            ->map(fn($case) => [
                'value' => $case->value,
                'label' => $case->label(),
            ]);
    }
}
