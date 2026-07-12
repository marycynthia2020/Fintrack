<?php
namespace FinTrack\FinLib\Enums;

enum IncomeType: string
{
    case Salary = 'salary';
    case Sales = 'sales';
    case Investment = 'investment';
    case Others = 'others';

    public function label(): string
    {
        return match($this) {
            self::Salary =>'Salaries & Wages',
            self::Sales => 'Sales & Revenue',
            self::Investment => 'Investment Income',
            self::Others => 'Others',
        };
    }
}

