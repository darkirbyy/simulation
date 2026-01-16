<?php

declare(strict_types=1);

namespace App\Enum\Necesse;

enum SexEnum: string
{
    case Female = 'F';
    case Male = 'M';
    case Undetermined = 'U';
}
