<?php

declare(strict_types=1);

namespace App\Dto\Necesse\SelfManaged;

use App\Entity\Necesse\MinMax;
use App\Entity\Necesse\Sim;
use App\Enum\Necesse\SexEnum;
use App\Enum\Necesse\TypeEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Random\IntervalBoundary;
use Random\Randomizer;

abstract class LivingBeing
{
    public function __construct(protected Sim $sim, protected Randomizer $randomizer, protected Henhouse $henhouse)
    {
    }

    abstract public function initialize(...$args): void;

    abstract public function tick(): void;

    abstract public function getSex(): SexEnum;

    abstract public function getType(): TypeEnum;

    protected function randomBetween(MinMax $minMax): int
    {
        return $this->randomizer->getInt($minMax->getMin(), $minMax->getMax());
    }

    protected function randomProba(float $proba): bool
    {
        return $this->randomizer->getFloat(0, 1, IntervalBoundary::ClosedClosed) <= $proba;
    }

    protected function randomElement(ArrayCollection $array): mixed
    {
        return $array->get($array->getKeys()[$this->randomizer->getInt(0, $array->count() - 1)]);
    }
}
