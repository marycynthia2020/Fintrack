<?php

namespace FinTrack\FinLib\Services;

use FinTrack\FinLib\Models\Account;
use FinTrack\FinLib\Models\Expense;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use FinTrack\FinLib\Enums\ExpenseType;
class ExpenseService
{
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            Account::firstOrCreate(
                ['organization_id' => $data['organization_id']],
                ['balance' => 0.00]
            );

            return Expense::create($data);
        });
    }

    public function update(Expense $expense, array $data)
    {
        return DB::transaction(function () use ($expense, $data) {
            $expense->update($data);
            return $expense;
        });
    }

    public function delete(Expense $expense)
    {
        return DB::transaction(function () use ($expense) {
            if (Auth::check()) {
                $expense->updateQuietly(['updated_by' => Auth::id()]);
            }
            $expense->delete();
            return $expense;
        });
    }

    public function list(array $filters = [])
    {
        $query = Expense::query();

        if (isset($filters['organization_id'])) {
            $query->ofOrganization($filters['organization_id']);
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

         return Expense::query()
        ->when($orgId, fn($query) => $query->ofOrganization($orgId))
        ->findOrFail($id);
    }

     public function categories() 
        {
            return collect(ExpenseType::cases())
                ->map(fn ($case) =>[
                    'value' =>$case->value,
                    'label' => $case->label(),
                ]);
        }
}
