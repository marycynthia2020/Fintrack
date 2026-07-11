<?php

namespace FinTrack\FinLib\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'balance' => app('fin-lib')->formatAmount((float) $this['balance']),
            'total_income' => app('fin-lib')->formatAmount((float) $this['total_income']),
            'total_expense' => app('fin-lib')->formatAmount((float) $this['total_expense']),
            'income_count' => (int) $this['income_count'],
            'expense_count' => (int) $this['expense_count'],
            'total_transactions' => (int) $this['total_transactions'],
        ];
    }
}
