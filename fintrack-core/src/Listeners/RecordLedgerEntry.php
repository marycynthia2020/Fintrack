<?php

namespace FinTrack\Core\Listeners;

use FinTrack\Core\Events\ModelCreated;
use FinTrack\FinLib\Enums\LedgerEventType;
use FinTrack\FinLib\Enums\LedgerType;
use FinTrack\FinLib\Models\Account;
use FinTrack\FinLib\Models\Expense;
use FinTrack\FinLib\Models\Income;
use FinTrack\FinLib\Models\Ledger;
use Illuminate\Support\Facades\DB;

class RecordLedgerEntry
{
    public function handle(ModelCreated $event): void
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

        DB::transaction(function () use ($model, $type) {
            $account = Account::where('organization_id', $model->organization_id)
                ->lockForUpdate()
                ->first();

            if (! $account) {
                return;
            }

            $previousBalance = $account->balance;
            $newBalance = $type === LedgerType::Credit
                ? $previousBalance + $model->amount
                : $previousBalance - $model->amount;

            $account->update(['balance' => $newBalance]);

            Ledger::create([
                'organization_id' => $model->organization_id,
                'amount' => $model->amount,
                'ledgerable_type' => $model::class,
                'ledgerable_id' => $model->id,
                'type' => $type->value,
                'description' => $model->description,
                'event_type' => LedgerEventType::Created->value,
                'created_by' => $model->created_by,
                'processed_at' => now(),
            ]);
        });
    }
}
