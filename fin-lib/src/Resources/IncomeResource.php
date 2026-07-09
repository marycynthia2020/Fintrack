<?php

namespace FinTrack\FinLib\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use FinTrack\Core\Resources\UserResource;

class IncomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'amount' => (float) $this->amount,
            'description' => $this->description,
            'type' => $this->type,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            
            // Optional relationships
            'creator' => new UserResource($this->whenLoaded('createdBy')),
            'updater' => new UserResource($this->whenLoaded('updatedBy')),
        ];
    }
}
