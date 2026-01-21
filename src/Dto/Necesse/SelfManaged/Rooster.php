<?php

declare(strict_types=1);

namespace App\Dto\Necesse\SelfManaged;

use App\Enum\Necesse\SexEnum;
use App\Enum\Necesse\TypeEnum;

class Rooster extends LivingBeing
{
    private int $timeBeforeFertilize;

    public function initialize(...$args): void
    {
        // At the beginning, the rooster can directly fertilized an hen, then add the rooster to the pool
        $this->timeBeforeFertilize = 1;
        $this->henhouse->livingBeings->add($this);

        // If there is a surplus of male (hen or male chick), remove a rooster (random or the one with the bigger timer)
        // Careful : surplus of +1 is okay because at this point, the chick giving this rooster is still present in the pool
        if ($this->henhouse->livingBeings->filter(fn (LivingBeing $l) => SexEnum::Male == $l->getSex())->count() >= $this->henhouse->sim->getLimitRooster() + 2) {
            $roosters = $this->henhouse->livingBeings->filter(fn (LivingBeing $l) => $l instanceof Rooster);
            $this->henhouse->livingBeings->removeElement($this->henhouse->randomElement($roosters));
            $this->henhouse->producedMeat++;
        }
    }

    public function act(): void
    {
        if (0 === $this->timeBeforeFertilize) {
            // When timer hit 0, choose a random not fertilized hen and fertilized it, or wait one step if none is available
            $hensVirgo = $this->henhouse->livingBeings->filter(fn (LivingBeing $l) => $l instanceof Hen)->filter(fn (Hen $h) => !$h->getFertilized());
            if ($hensVirgo->count() > 0) {
                $this->henhouse->randomElement($hensVirgo)->fertilize();
                $this->timeBeforeFertilize = $this->henhouse->randomBetween($this->henhouse->sim->getWorld()->getRoosterToFertilize());
            } else {
                $femaleChicks = $this->henhouse->livingBeings->filter(fn (LivingBeing $l) => $l instanceof Chick && SexEnum::Female == $l->getSex());
                $nextFemaleChickToAdult = $femaleChicks->reduce(fn (?LivingBeing $min, LivingBeing $l) => null === $min || $l->getTimer() < $min->getTimer() ? $l : $min);
                $nextTimer = min($nextFemaleChickToAdult?->getTimer() ?? PHP_INT_MAX, $this->henhouse->sim->getWorld()->getChickToChicken()->getMin());
                $this->timeBeforeFertilize = max($nextTimer, 1);
            }
        }
    }

    public function getSex(): SexEnum
    {
        return SexEnum::Male;
    }

    public function getType(): TypeEnum
    {
        return TypeEnum::Chicken;
    }

    public function getTimer(): int
    {
        return $this->timeBeforeFertilize;
    }

    public function tickTimer(int $deltaTime): void
    {
        $this->timeBeforeFertilize -= $deltaTime;
    }
}
