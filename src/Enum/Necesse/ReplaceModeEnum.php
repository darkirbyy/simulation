<?php

declare(strict_types=1);

namespace App\Enum\Necesse;

enum ReplaceModeEnum: string
{
    case Random = 'Random';
    case Optimal = 'Optimal';
}
