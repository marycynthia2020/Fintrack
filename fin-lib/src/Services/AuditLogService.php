<?php

namespace FinTrack\FinLib\Services;

use FinTrack\FinLib\Events\AuditLogCreated;
use FinTrack\FinLib\Models\AuditLog;

class AuditLogService
{
    public function create(array $data): AuditLog
    {
        $auditLog = AuditLog::create($data);

        AuditLogCreated::dispatch($auditLog);

        return $auditLog;
    }

    public function update(AuditLog $auditLog, array $data)
    {
        //
    }

    public function delete(AuditLog $auditLog)
    {
        //
    }

    public function list(array $filters = [])
    {
        return AuditLog::query()
            ->when($filters['organization_id'] ?? null, fn ($query, $organizationId) => $query->where('organization_id', $organizationId))
            ->get();
    }

    public function find(string $id): ?AuditLog
    {
        return AuditLog::find($id);
    }
}
