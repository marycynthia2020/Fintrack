<?php

namespace FinTrack\FinLib\Services;

use FinTrack\FinLib\Models\Account;

class AccountService
{
     public function find(string $organizationId)
    {
         return Account::firstOrCreate(
            ['organization_id' => $organizationId],
            ['balance' => 0.00]
        );
    }

    public function create(array $data)
    {
        //
    }

    public function update(Account $account, array $data)
    {
        //
    }

    public function delete(Account $account)
    {
        //
    }

    public function list(array $filters = [])
    {
        //
    }

    public function forceDelete(Account $account)
    {
        //
    }
}
