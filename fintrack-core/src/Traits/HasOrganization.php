<?php 
namespace FinTrack\Core\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasOrganization
{
    public function organization()
    {
        return $this->belongsTo(\FinTrack\Core\Models\Organization::class, 'organization_id', 'id');
    }

    public function scopeOfOrganization(Builder $query, string $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }
}