<?php 
namespace FinTrack\Core\Traits;


trait HasOrganization
{
    public function organization()
    {
        return $this->belongsTo(\FinTrack\Core\Models\Organization::class, 'organization_id', 'id');
    }
}