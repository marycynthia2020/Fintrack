<?php

namespace FinTrack\FinLib\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use FinTrack\Core\Resources\UserResource;

class LedgerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $typeString = is_object($this->type) && isset($this->type->value) ? $this->type->value : $this->type;

        return [
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'amount' => app('fin-lib')->formatAmount((float)$this->amount),
            'ledgerable_type' => $this->ledgerable_type,
            'ledgerable_id' => $this->ledgerable_id,
            'type' => $typeString === 'credit' ? 'Income' : ($typeString === 'debit' ? 'Expenses' : $typeString),
            'description' => $this->description,
            'event_type' => $this->event_type,
            'created_by' => $this->created_by,
            'processed_at' => $this->processed_at?->toIso8601String(),
            'metadata' => $this->metadata,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'creator' => new UserResource($this->whenLoaded('createdBy')),
        ];
    }
}
