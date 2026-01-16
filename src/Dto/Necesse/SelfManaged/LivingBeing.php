<?php

declare(strict_types=1);

namespace App\Dto\Necesse\SelfManaged;

use App\Entity\Necesse\Sim;
use App\Enum\Necesse\SexEnum;
use App\Enum\Necesse\TypeEnum;

abstract class LivingBeing
{
    public function __construct(protected Sim $sim, protected Henhouse $henhouse)
    {
    }

    abstract public function initialize(...$args): void;

    abstract public function tick(): void;

    abstract public function getSex(): SexEnum;

    abstract public function getType(): TypeEnum;
}
