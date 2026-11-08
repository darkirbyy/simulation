<?php

declare(strict_types=1);

namespace App\Enum\Apad;

enum MonthEnum: string
{
    case Jan = 'jan';
    case Feb = 'feb';
    case Mar = 'mar';
    case Apr = 'apr';
    case May = 'may';
    case Jun = 'jun';
    case Jul = 'jul';
    case Aug = 'aug';
    case Sep = 'sep';
    case Oct = 'oct';
    case Nov = 'nov';
    case Dec = 'dec';

    public static function fromMonthNumber(int $monNumber): self
    {
        return match ($monNumber) {
            1 => self::Jan,
            2 => self::Feb,
            3 => self::Mar,
            4 => self::Apr,
            5 => self::May,
            6 => self::Jun,
            7 => self::Jul,
            8 => self::Aug,
            9 => self::Sep,
            10 => self::Oct,
            11 => self::Nov,
            12 => self::Dec,
            default => new \RuntimeException('$monthNumber must be between 1 and 12'),
        };
    }
}
