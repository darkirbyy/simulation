<?php

declare(strict_types=1);

namespace App\Dto\Necesse\SelfManaged;

use App\Enum\Necesse\SexEnum;
use App\Enum\Necesse\TypeEnum;

class Hen extends LivingBeing
{
    private int $timeBeforeLay;
    private bool $fertilized;

    public function initialize(...$args): void
    {
        // By default, an hen is not fertilized, and the sex/time before first egg are randomized, then add the hen to the pool
        $this->fertilized = false;
        $this->timeBeforeLay = $this->henhouse->randomBetween($this->henhouse->sim->getWorld()->getHenToLay());
        $this->henhouse->livingBeings->add($this);

        // If there is a surplus of female (hen or female chick), remove a hen (random or the one with the bigger timer)
        // Careful : surplus of +1 is okay because at this point, the chick giving this hen is still present in the pool
        if ($this->henhouse->livingBeings->filter(fn (LivingBeing $l) => SexEnum::Female == $l->getSex())->count() >= $this->henhouse->sim->getLimitHen() + 2) {
            $hens = $this->henhouse->livingBeings->filter(fn (LivingBeing $l) => $l instanceof Hen);
            $this->henhouse->livingBeings->removeElement($this->henhouse->randomElement($hens));
            $this->henhouse->producedMeat++;
        }
    }

    public function act(): void
    {
        if (0 === $this->timeBeforeLay) {
            // When timer hit 0, create an new egg, add it to the pool, and randomize again the timer
            $egg = new Egg($this->henhouse);
            $egg->initialize($this->fertilized);
            $this->timeBeforeLay = $this->henhouse->randomBetween($this->henhouse->sim->getWorld()->getHenToLay());
        }
    }

    public function getSex(): SexEnum
    {
        return SexEnum::Female;
    }

    public function getType(): TypeEnum
    {
        return TypeEnum::Chicken;
    }

    public function getTimer(): int
    {
        return $this->timeBeforeLay;
    }

    public function tickTimer(int $deltaTime): void
    {
        $this->timeBeforeLay -= $deltaTime;
    }

    public function getFertilized(): bool
    {
        return $this->fertilized;
    }

    public function fertilize(): void
    {
        $this->fertilized = true;
    }
}
