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
    /**
     * Then househouse is just a container passed to every living being, containing the simulation parameters, the randomizer,
     * a pool where all living being are, and two variables to count the products.
     *
     * @param Sim             $sim          simulation parameters
     * @param Randomizer      $randomizer   a unique randomizer used by every living being
     * @param ArrayCollection $livingBeings the pool rergouping all living being
     * @param int             $producedEgg  number of egg products
     * @param int             $producedMeat number of meat products
     */
    public function __construct(public Sim $sim, public Randomizer $randomizer, public ArrayCollection $livingBeings, public int $producedEgg, public int $producedMeat) {}

    /**
     * Randomize an int between a max and a min.
     *
     * @param MinMax $minMax the boundaries
     */
    public function randomBetween(MinMax $minMax): int
    {
        return $this->randomizer->getInt($minMax->getMin(), $minMax->getMax());
    }

    /**
     * Randomize a bool with a given probability of true.
     *
     * @param float $proba probability of true
     */
    public function randomProba(float $proba): bool
    {
        return $this->randomizer->getFloat(0, 1, IntervalBoundary::ClosedClosed) <= $proba;
    }

    /**
     * Retrieve a random element of a collection.
     *
     * @param ArrayCollection $array collection to pull an element from
     */
    public function randomElement(ArrayCollection $array): mixed
    {
        return $array->get($array->getKeys()[$this->randomizer->getInt(0, $array->count() - 1)]);
    }
}
