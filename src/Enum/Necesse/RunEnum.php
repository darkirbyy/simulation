<?php

declare(strict_types=1);

namespace App\Enum\Necesse;

use App\Service\Necesse\RunSelfManaged;
use App\Service\Necesse\RunTest;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum RunEnum: string implements TranslatableInterface
{
    case SelfManaged = 'SM';
    case Centralized = 'CE';

    public function trans(TranslatorInterface $trans, ?string $locale = null): string
    {
        return match ($this) {
            self::SelfManaged => 'Auto-géré',
            self::Centralized => 'Centralisé',
        };
    }

    public function toClass(): string
    {
        return match ($this) {
            self::SelfManaged => RunSelfManaged::class,
            self::Centralized => RunTest::class,
        };
    }
}
