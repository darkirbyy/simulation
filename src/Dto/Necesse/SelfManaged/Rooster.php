<?php

declare(strict_types=1);

namespace App\Dto\Necesse\SelfManaged;

use App\Enum\Necesse\ReplaceModeEnum;
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
            if (ReplaceModeEnum::Random == $this->henhouse->sim->getWorld()->getReplaceMode()) {
                $roosterToRemove = $this->henhouse->randomElement($roosters);
            } else {
                $roosterToRemove = $roosters->reduce(fn (?LivingBeing $max, LivingBeing $l) => null === $max || $l->getTimer() > $max->getTimer() ? $l : $max);
            }
            $this->henhouse->producedMeat++;
            $this->henhouse->livingBeings->removeElement($roosterToRemove);
        }
    }

    public function tick(int $deltaTime): void
    {
        // Timer before fertilizing another hen
        $this->timeBeforeFertilize -= $deltaTime;

        if (0 === $this->timeBeforeFertilize) {
            // When timer hit 0, choose a random not fertilized hen and fertilized it, or wait one step if none is available
            $hensVirgo = $this->henhouse->livingBeings->filter(fn (LivingBeing $l) => $l instanceof Hen)->filter(fn (Hen $h) => !$h->getFertilized());
            if ($hensVirgo->count() > 0) {
                $this->henhouse->randomElement($hensVirgo)->fertilize();
                $this->timeBeforeFertilize = $this->henhouse->randomBetween($this->henhouse->sim->getWorld()->getRoosterToFertilize());
            } else {
                $this->timeBeforeFertilize = 1;
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
}
