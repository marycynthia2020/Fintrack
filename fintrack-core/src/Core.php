<?php

namespace FinTrack\Core;

use FinTrack\Core\Models\Organization;
use Illuminate\Support\Facades\Config;

class Core
{
    public function organization(string $id): ?Organization
    {
        return Organization::find($id);
    }

    public function currency(): string
    {
        return Config::get('fintrack-core.default_currency', 'USD');
    }

    public function timezone(): string
    {
        return Config::get('fintrack-core.default_timezone', 'UTC');
    }

    public function storageDisk(): string
    {
        return Config::get('fintrack-core.storage_disk', 'local');
    }
}
