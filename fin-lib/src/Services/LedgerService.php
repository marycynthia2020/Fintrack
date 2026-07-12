<?php

namespace FinTrack\FinLib\Services;

use FinTrack\FinLib\Models\Ledger;
use Illuminate\Support\Facades\Auth;

class LedgerService
{
    public function create(array $data)
    {
        //
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
        $query = Ledger::query();

        if (isset($filters['organization_id'])) {
            $query->where('organization_id', $filters['organization_id']);
        }

        if (isset($filters['type'])) {
            $type = strtolower($filters['type']);
            if ($type === 'income') {
                $query->where('type', 'credit');
            } elseif ($type === 'expense' || $type === 'expenses') {
                $query->where('type', 'debit');
            } else {
                $query->where('type', $type);
            }
        }

        if (isset($filters['event_type'])) {
            $query->where('event_type', $filters['event_type']);
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
        
        $query = Ledger::query();
        if ($orgId) {
            $query->where('organization_id', $orgId);
        }

        return $query->findOrFail($id);
    }
}
