<?php

declare(strict_types=1);

namespace App\Dto\Necesse\SelfManaged;

use App\Entity\Necesse\MinMax;
use App\Entity\Necesse\Sim;
use Doctrine\Common\Collections\ArrayCollection;
use Random\IntervalBoundary;
use Random\Randomizer;

class Henhouse
{
    public function __construct(public Sim $sim, public Randomizer $randomizer, public ArrayCollection $livingBeings, public int $producedEgg, public int $producedMeat)
    {
    }

    public function randomBetween(MinMax $minMax): int
    {
        return $this->randomizer->getInt($minMax->getMin(), $minMax->getMax());
    }

    public function randomProba(float $proba): bool
    {
        return $this->randomizer->getFloat(0, 1, IntervalBoundary::ClosedClosed) <= $proba;
    }

    public function randomElement(ArrayCollection $array): mixed
    {
        return $array->get($array->getKeys()[$this->randomizer->getInt(0, $array->count() - 1)]);
    }
}
