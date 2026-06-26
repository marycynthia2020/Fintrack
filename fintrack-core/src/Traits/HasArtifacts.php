<?php

namespace FinTrack\Core\Traits;

use FinTrack\Core\Models\Artifact;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasArtifacts
{
    public function artifacts(): MorphMany
    {
        return $this->morphMany(Artifact::class, 'artifactable');
    }

    public function addArtifact(array $attributes): Artifact
    {
        return $this->artifacts()->create($attributes);
    }

    public function removeArtifact(string $artifactId): bool
    {
        return (bool) $this->artifacts()->where('id', $artifactId)->delete();
    }

    public function artifactsByType(string $type): MorphMany
    {
        return $this->artifacts()->where('type', $type);
    }
}
