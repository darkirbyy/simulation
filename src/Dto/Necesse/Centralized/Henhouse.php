<?php

declare(strict_types=1);

namespace App\Dto\Necesse\Centralized;

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

    /**
     *  Create all timer array for each type of living beings, and counter for products
     *  and also store the sim parameter and the randomizer.
     */
    public function __construct(public Sim $sim, public Randomizer $randomizer)
    {
        $this->producedEgg = 0;
        $this->producedMeat = 0;
        $this->eggs = new TimerArray($sim->getLimitNest());
        $this->chicksFemale = new TimerArray(3 * $sim->getLimitHen());
        $this->chicksMale = new TimerArray(3 * $sim->getLimitRooster());
        $this->hensFertilized = new TimerArray($sim->getLimitHen());
        $this->hensVirgo = new TimerArray($sim->getLimitHen());
        $this->roosters = new TimerArray($sim->getLimitRooster());
    }

    /**
     * Add as much hens and roosters as stated by the initial conditions.
     */
    public function initiliaze(): void
    {
        for ($initialHen = 0; $initialHen < $this->sim->getInitialHen(); $initialHen++) {
            $this->hensVirgo->addTimer($this->randomBetween($this->sim->getWorld()->getHenToLay()));
        }
        for ($initialRooster = 0; $initialRooster < $this->sim->getInitialRooster(); $initialRooster++) {
            $this->roosters->addTimer(1);
        }
    }

    /**
     * Tick the timers of each type of living being.
     */
    public function tickTimersArray(int $deltaTime): void
    {
        $this->eggs->tickTimers($deltaTime);
        $this->chicksFemale->tickTimers($deltaTime);
        $this->chicksMale->tickTimers($deltaTime);
        $this->hensFertilized->tickTimers($deltaTime);
        $this->hensVirgo->tickTimers($deltaTime);
        $this->roosters->tickTimers($deltaTime);
    }

    /**
     * Main logic, defining the behavior of each type of living being when the timer hit 0.
     */
    public function dueTimersArray(): void
    {
        foreach ($this->eggs->dueTimersIndexes() as $i) {
            $this->eggs->removeTimer($i);
            if ($this->randomProba($this->sim->getWorld()->getEggToFemale())) {
                if ($this->chicksFemale->addTimer($this->randomBetween($this->sim->getWorld()->getChickToChicken()))) {
                    if (
                        $this->chicksFemale->active + $this->hensVirgo->active + $this->hensFertilized->active > $this->sim->getLimitHen() &&
                        $this->hensVirgo->active + $this->hensFertilized->active > 0
                    ) {
                        $randomHen = $this->randomBetween((new MinMax())->setMin(1)->setMax($this->hensVirgo->active + $this->hensFertilized->active));
                        if ($randomHen <= $this->hensVirgo->active) {
                            $this->hensVirgo->removeTimer($this->randomElement($this->hensVirgo->activeTimersIndexes()));
                        } else {
                            $this->hensFertilized->removeTimer($this->randomElement($this->hensFertilized->activeTimersIndexes()));
                        }
                        $this->producedMeat++;
                    }
                }
            } else {
                if ($this->chicksMale->addTimer($this->randomBetween($this->sim->getWorld()->getChickToChicken()))) {
                    if ($this->chicksMale->active + $this->roosters->active > $this->sim->getLimitRooster() && $this->roosters->active > 0) {
                        $this->roosters->removeTimer($this->randomElement($this->roosters->activeTimersIndexes()));
                        $this->producedMeat++;
                    }
                }
            }
        }

        foreach ($this->chicksFemale->dueTimersIndexes() as $i) {
            $this->chicksFemale->removeTimer($i);
            $this->hensVirgo->addTimer($this->randomBetween($this->sim->getWorld()->getHenToLay()));
            if ($this->chicksFemale->active + $this->hensVirgo->active + $this->hensFertilized->active > $this->sim->getLimitHen()) {
                $randomHen = $this->randomBetween((new MinMax())->setMin(1)->setMax($this->hensVirgo->active + $this->hensFertilized->active));
                if ($randomHen <= $this->hensVirgo->active) {
                    $this->hensVirgo->removeTimer($this->randomElement($this->hensVirgo->activeTimersIndexes()));
                } else {
                    $this->hensFertilized->removeTimer($this->randomElement($this->hensFertilized->activeTimersIndexes()));
                }
                $this->producedMeat++;
            }
        }

        foreach ($this->chicksMale->dueTimersIndexes() as $i) {
            $this->chicksMale->removeTimer($i);
            $this->roosters->addTimer(1);
            if ($this->chicksMale->active + $this->roosters->active > $this->sim->getLimitRooster()) {
                $this->roosters->removeTimer($this->randomElement($this->roosters->activeTimersIndexes()));
                $this->producedMeat++;
            }
        }

        foreach ($this->hensVirgo->dueTimersIndexes() as $i) {
            $this->hensVirgo->removeTimer($i);
            $this->hensVirgo->addTimer($this->randomBetween($this->sim->getWorld()->getHenToLay()));
            $this->producedEgg++;
        }

        foreach ($this->hensFertilized->dueTimersIndexes() as $i) {
            $this->hensFertilized->removeTimer($i);
            $this->hensFertilized->addTimer($this->randomBetween($this->sim->getWorld()->getHenToLay()));
            if (!$this->eggs->addTimer($this->randomBetween($this->sim->getWorld()->getEggToChick()))) {
                $this->producedEgg++;
            }
        }

        foreach ($this->roosters->dueTimersIndexes() as $i) {
            if ($this->hensVirgo->active > 0) {
                $randomIndex = $this->randomElement($this->hensVirgo->activeTimersIndexes());
                $this->hensFertilized->addTimer($this->hensVirgo->fixedArray[$randomIndex]);
                $this->hensVirgo->removeTimer($randomIndex);
                $this->roosters->removeTimer($i);
                $this->roosters->addTimer($this->randomBetween($this->sim->getWorld()->getRoosterToFertilize()));
            } else {
                $this->roosters->removeTimer($i);
                $newTimer = min($this->chicksFemale->minTimers(), $this->sim->getWorld()->getEggToChick()->getMin());
                $this->roosters->addTimer(max($newTimer, 1));
            }
        }
    }

    /**
     * Get the min timer of each type of living being.
     */
    public function minTimersArray(): int
    {
        $minEggs = $this->eggs->minTimers();
        $minChicksFemale = $this->chicksFemale->minTimers();
        $minChicksMale = $this->chicksMale->minTimers();
        $minHensFertilized = $this->hensFertilized->minTimers();
        $minHensVirgo = $this->hensVirgo->minTimers();
        $minrRoosters = $this->roosters->minTimers();

        return min($minEggs, $minChicksFemale, $minChicksMale, $minHensFertilized, $minHensVirgo, $minrRoosters);
    }

    /**
     * Randomize an int between a max and a min.
     *
     * @param MinMax $minMax the boundaries
     */
    private function randomBetween(MinMax $minMax): int
    {
        return $this->randomizer->getInt($minMax->getMin(), $minMax->getMax());
    }

    /**
     * Randomize a bool with a given probability of true.
     *
     * @param float $proba probability of true
     */
    private function randomProba(float $proba): bool
    {
        return $this->randomizer->getFloat(0, 1, IntervalBoundary::ClosedClosed) <= $proba;
    }

    /**
     * Retrieve a random element of an array.
     *
     * @param ArrayCollection $array array to pull an element from
     */
    private function randomElement(array $array): int
    {
        return $array[$this->randomizer->getInt(0, count($array) - 1)];
    }
}
