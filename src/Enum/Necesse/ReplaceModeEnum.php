<?php

declare(strict_types=1);

namespace App\Enum;

enum ReplaceModeEnum: string
{
    case Random = 'Random';
    case Optimal = 'Optimal';
}
