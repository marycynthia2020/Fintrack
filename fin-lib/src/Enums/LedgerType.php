<?php

namespace FinTrack\FinLib\Enums;

enum LedgerType: string
{
    case Credit = 'credit';
    case Debit = 'debit';
}
