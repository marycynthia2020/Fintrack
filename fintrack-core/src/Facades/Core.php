<?php

namespace FinTrack\Core\Facades;

use Illuminate\Support\Facades\Facade;

class Core extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'fintrack-core';
    }
}
