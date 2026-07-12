<?php

namespace FinTrack\FinLib\Services;

use FinTrack\FinLib\Models\Account;
use FinTrack\FinLib\Models\Income;
use FinTrack\FinLib\Models\Expense;

class DashboardService
{
    public function getSummary(string $organizationId): array
    {
        $account = Account::firstOrCreate(
            ['organization_id' => $organizationId],
            ['balance' => 0.00]
        );

        $incomeStats = Income::where('organization_id', $organizationId)
            ->selectRaw('COALESCE(SUM(amount), 0) as total, COUNT(*) as count')
            ->first();

        $expenseStats = Expense::where('organization_id', $organizationId)
            ->selectRaw('COALESCE(SUM(amount), 0) as total, COUNT(*) as count')
            ->first();

        $incomeCount = (int) $incomeStats->count;
        $expenseCount = (int) $expenseStats->count;

        return [
            'balance' => (float) $account->balance,
            'total_income' => (float) $incomeStats->total,
            'total_expenses' => (float) $expenseStats->total,
            'income_count' => $incomeCount,
            'expenses_count' => $expenseCount,
            'total_transactions' => $incomeCount + $expenseCount,
        ];
    }
}
