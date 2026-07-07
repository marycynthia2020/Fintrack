<?php

namespace FinTrack\FinLib\Services;

use FinTrack\FinLib\Events\AccountCreated;
use FinTrack\FinLib\Events\AccountDeleted;
use FinTrack\FinLib\Events\AccountUpdated;
use FinTrack\FinLib\Models\Account;

class AccountService
{
    public function create(array $data): Account
    {
        $account = Account::create($data);

        AccountCreated::dispatch($account);

        return $account;
    }

    public function update(Account $account, array $data): Account
    {
        $account->update($data);

        AccountUpdated::dispatch($account);

        return $account;
    }

    public function delete(Account $account): bool
    {
        $deleted = (bool) $account->delete();

        AccountDeleted::dispatch($account);

        return $deleted;
    }

    public function list(array $filters = [])
    {
        return Account::query()
            ->when($filters['organization_id'] ?? null, fn ($query, $organizationId) => $query->where('organization_id', $organizationId))
            ->get();
    }

    public function find(string $id): ?Account
    {
        return Account::find($id);
    }
}
