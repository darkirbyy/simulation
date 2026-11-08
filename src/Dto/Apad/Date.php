<?php

declare(strict_types=1);

namespace App\Dto\Apad;

use App\Enum\Apad\MonthEnum;

class Date
{
    public function __construct(public int $day, public MonthEnum $month)
    {
        if ($day < 0 || $day > 31) {
            throw new \RuntimeException('$day must be between 1 and 31.');
        }
    }

    public function toFileName(): string
    {
        return $this->day . '-' . $this->month->value . '.txt';
    }

    public function toCommand(): string
    {
        return $this->day . ' ' . $this->month->value;
    }

    public static function fromDateTime(\DateTime $dateTime): Date
    {
        return new Date((int) $dateTime->format('d'), MonthEnum::fromMonthNumber((int) $dateTime->format('m')));
    }
}
