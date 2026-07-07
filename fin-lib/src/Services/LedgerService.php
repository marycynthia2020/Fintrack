<?php

namespace FinTrack\FinLib\Services;

use FinTrack\FinLib\Events\LedgerCreated;
use FinTrack\FinLib\Models\Ledger;

class LedgerService
{
    public function create(array $data): Ledger
    {
        $ledger = Ledger::create($data);

        LedgerCreated::dispatch($ledger);

        return $ledger;
    }

    public function update(Ledger $ledger, array $data)
    {
        //
    }

    public function delete(Ledger $ledger)
    {
        //
    }

    public function list(array $filters = [])
    {
        return Ledger::query()
            ->when($filters['organization_id'] ?? null, fn ($query, $organizationId) => $query->where('organization_id', $organizationId))
            ->get();
    }

    public function find(string $id): ?Ledger
    {
        return Ledger::find($id);
    }
}
