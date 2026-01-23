<?php

declare(strict_types=1);

namespace App\Service\Necesse;

use App\Dto\Necesse\SelfManaged\Hen;
use App\Dto\Necesse\SelfManaged\Henhouse;
use App\Dto\Necesse\SelfManaged\LivingBeing;
use App\Dto\Necesse\SelfManaged\Rooster;
use App\Entity\Necesse\Bar;
use App\Entity\Necesse\Sim;
use App\Enum\Necesse\SexEnum;
use App\Enum\Necesse\TypeEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Random\Randomizer;

class RunSelfManaged implements RunInterface
{
    private Henhouse $henhouse;

    public function __construct() {}

    public function start(Sim $sim, Randomizer $randomizer): void
    {
        // Initialize the henhouse with sim params, the randomizer, empty pool and 0 products
        $this->henhouse = new Henhouse($sim, $randomizer, new ArrayCollection(), 0, 0);

        $this->henhouse->initiliaze();
    }

    public function update(int $deltaTime): array
    {
        // Update the timer of each living being in the pool
        foreach ($this->henhouse->livingBeings as $livingBeing) {
            $livingBeing->tickTimer($deltaTime);
        }

        // Start the action of each living being in the pool
        foreach ($this->henhouse->livingBeings as $livingBeing) {
            $livingBeing->act();
        }

        // Calculate next timer as the min of all timers of living beings in the pool
        $nextDeltaTime = PHP_INT_MAX;
        foreach ($this->henhouse->livingBeings as $livingBeing) {
            $nextDeltaTime = min($livingBeing->getTimer(), $nextDeltaTime);
        }

        // return the bar corresponding to the current state
        return [$this->henhouseToBar(), $nextDeltaTime];
    }

    public function stop(): void
    {
        // nothing to do at the end
    }

    /**
     * Convert the henhouse pool and products count to a bar.
     */
    private function henhouseToBar(): Bar
    {
        $bar = new Bar();
        $bar->setProducedEgg($this->henhouse->producedEgg);
        $bar->setProducedMeat($this->henhouse->producedMeat);
        $bar->setLivingEgg($this->henhouse->livingBeings->filter(fn(LivingBeing $l) => TypeEnum::Egg == $l->getType())->count());
        $bar->setLivingChickFemale($this->henhouse->livingBeings->filter(fn(LivingBeing $l) => TypeEnum::Chick == $l->getType() && SexEnum::Female == $l->getSex())->count());
        $bar->setLivingChickMale($this->henhouse->livingBeings->filter(fn(LivingBeing $l) => TypeEnum::Chick == $l->getType() && SexEnum::Male == $l->getSex())->count());
        $bar->setLivingHenFertilized($this->henhouse->livingBeings->filter(fn(LivingBeing $l) => $l instanceof Hen)->filter(fn(Hen $h) => $h->getFertilized())->count());
        $bar->setLivingHenVirgo($this->henhouse->livingBeings->filter(fn(LivingBeing $l) => $l instanceof Hen)->filter(fn(Hen $h) => !$h->getFertilized())->count());
        $bar->setLivingRooster($this->henhouse->livingBeings->filter(fn(LivingBeing $l) => $l instanceof Rooster)->count());

        return $bar;
    }
}
