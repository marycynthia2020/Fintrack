<?php

namespace FinTrack\FinLib\Enums;

enum ExpenseType: string
{
    case Rent = 'rent';
    case Utilities = 'utilities';
    case Transportation = 'transportation';
    case Groceries = 'groceries';
    case Taxes = 'taxes';
    case Others = 'others';
    case Maintenance = 'maintenance';

    public function label(): string 
    {    
        return match($this) {
            self::Rent=>'Rent',
            self::Utilities=>'Utilities & Bills', 
            self::Transportation=>'Transportation & Travel',
            self::Groceries=>'Groceries & Food',
            self::Taxes=>'Taxes & Fees',
            self::Maintenance=>'Maintenance & Repairs',
            self::Others=>'Others',
        };
    }
}
