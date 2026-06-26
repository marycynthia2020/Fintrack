<?php

namespace FinTrack\FinLib\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string formatAmount(float $amount, string $currency = 'USD', int $decimals = 2)
 * @method static int    toMinorUnit(float $amount, int $decimals = 2)
 * @method static float  fromMinorUnit(int $amount, int $decimals = 2)
 * @method static float  roundAmount(float $amount, int $decimals = 2)
 *
 * @see \FinTrack\FinLib\FinLib
 */
class FinLib extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'fin-lib';
    }
}
