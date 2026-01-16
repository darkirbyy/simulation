<?php

declare(strict_types=1);

namespace App\Enum\Necesse;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ReplaceModeEnum: string implements TranslatableInterface
{
    case Random = 'Random';
    case Optimal = 'Optimal';

    public function trans(TranslatorInterface $trans, ?string $locale = null): string
    {
        return match ($this) {
            self::Random => 'Aléatoire',
            self::Optimal => 'Optimal',
        };
    }
}
