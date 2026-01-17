<?php

declare(strict_types=1);

namespace App\Enum\Necesse;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum RunEnum: string implements TranslatableInterface
{
    case SelfManaged = 'SM';
    case Centralized = 'CE';

    public function trans(TranslatorInterface $trans, ?string $locale = null): string
    {
        return match ($this) {
            self::SelfManaged => 'Auto-managé',
            self::Centralized => 'Centralisé',
        };
    }
}
