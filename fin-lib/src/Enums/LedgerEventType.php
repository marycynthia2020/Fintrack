<?php

namespace FinTrack\FinLib\Enums;

enum LedgerEventType: string
{
    case Created = 'created';
    case Updated = 'updated';
    case Deleted = 'deleted';
}
