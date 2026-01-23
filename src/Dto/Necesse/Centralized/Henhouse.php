<?php

declare(strict_types=1);

namespace App\Dto\Necesse\Centralized;

use App\Dto\Necesse\SelfManaged\TimerArray;
use App\Entity\Necesse\MinMax;
use App\Entity\Necesse\Sim;
use Doctrine\Common\Collections\ArrayCollection;
use Random\IntervalBoundary;
use Random\Randomizer;

class Henhouse
{
    public int $producedEgg;
    public int $producedMeat;
    public TimerArray $eggs;
    public TimerArray $chicksFemale;
    public TimerArray $chicksMale;
    public TimerArray $hensVirgo;
    public TimerArray $hensFertilized;
    public TimerArray $roosters;

    public function __construct(public Sim $sim, public Randomizer $randomizer)
    {
        $this->producedEgg = 0;
        $this->producedMeat = 0;
        $this->eggs = new TimerArray($sim->getLimitNest());
        $this->chicksFemale = new TimerArray(3 * $sim->getLimitHen());
        $this->chicksMale = new TimerArray(3 * $sim->getLimitRooster());
        $this->hensVirgo = new TimerArray($sim->getLimitHen());
        $this->hensFertilized = new TimerArray($sim->getLimitHen());
        $this->roosters = new TimerArray($sim->getLimitRooster());
    }

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
     * Retrieve a random element of an array.
     *
     * @param ArrayCollection $array array to pull an element from
     */
    public function randomElement(array $array): int
    {
        return $array[$this->randomizer->getInt(0, count($array) - 1)];
    }
}
