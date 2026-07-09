<?php

namespace FinTrack\Core\Listeners;

use FinTrack\Core\Events\ModelDeleted;
use FinTrack\FinLib\Enums\LedgerEventType;
use FinTrack\FinLib\Enums\LedgerType;
use FinTrack\FinLib\Models\Account;
use FinTrack\FinLib\Models\Expense;
use FinTrack\FinLib\Models\Income;
use FinTrack\FinLib\Models\Ledger;
use Illuminate\Support\Facades\DB;

class DeleteLedgerEntry
{
    public function handle(ModelDeleted $event): void
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

            if ($account) {
                $adjustment = $type === LedgerType::Credit ? -$model->amount : $model->amount;
                $account->update(['balance' => $account->balance + $adjustment]);
            }

            Ledger::create([
                'organization_id' => $model->organization_id,
                'amount' => $model->amount,
                'ledgerable_type' => $model::class,
                'ledgerable_id' => $model->id,
                'type' => $type->value,
                'description' => $model->description,
                'event_type' => LedgerEventType::Deleted->value,
                'created_by' => $model->updated_by ?? $model->created_by,
                'processed_at' => now(),
            ]);
        });
    }
}
