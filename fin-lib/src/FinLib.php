<?php

namespace FinTrack\FinLib;

class FinLib
{
    public function formatAmount(float $amount, string $currency = 'USD', int $decimals = 2): string
    {
        return number_format($amount, $decimals) . ' ' . strtoupper($currency);
    }

    public function toMinorUnit(float $amount, int $decimals = 2): int
    {
        return (int) round($amount * pow(10, $decimals));
    }

    public function fromMinorUnit(int $amount, int $decimals = 2): float
    {
        return $amount / pow(10, $decimals);
    }

    public function roundAmount(float $amount, int $decimals = 2): float
    {
        return round($amount, $decimals);
    }
}
