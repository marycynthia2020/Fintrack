<?php

namespace FinTrack\Core\Listeners;

use FinTrack\Core\Events\ModelUpdated;
use FinTrack\FinLib\Enums\LedgerEventType;
use FinTrack\FinLib\Enums\LedgerType;
use FinTrack\FinLib\Models\Account;
use FinTrack\FinLib\Models\Expense;
use FinTrack\FinLib\Models\Income;
use FinTrack\FinLib\Models\Ledger;
use Illuminate\Support\Facades\DB;

class UpdateLedgerEntry
{
    public function handle(ModelUpdated $event): void
    {
        $model = $event->model;

        $type = match (true) {
            $model instanceof Income => LedgerType::Credit,
            $model instanceof Expense => LedgerType::Debit,
            default => null,
        };

        if ($type === null) {
            return;
        }

        // Get original and new amount to compute the difference
        $oldAmount = $event->original['amount'] ?? $model->amount;
        $newAmount = $model->amount;
        $difference = $newAmount - $oldAmount;

        DB::transaction(function () use ($model, $type, $difference) {
            $account = Account::where('organization_id', $model->organization_id)
                ->lockForUpdate()
                ->first();

            if ($account) {
                $adjustment = $type === LedgerType::Credit ? $difference : -$difference;
                $account->update(['balance' => $account->balance + $adjustment]);
            }

            Ledger::create([
                'organization_id' => $model->organization_id,
                'amount' => $model->amount,
                'ledgerable_type' => $model::class,
                'ledgerable_id' => $model->id,
                'type' => $type->value,
                'description' => $model->description,
                'event_type' => LedgerEventType::Updated->value,
                'created_by' => $model->updated_by ?? $model->created_by,
                'processed_at' => now(),
            ]);
        });
    }
}
