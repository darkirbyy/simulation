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
        $this->sex = $this->henhouse->randomProba($this->henhouse->sim->getWorld()->getEggToFemale()) ? SexEnum::Female : SexEnum::Male;
        $this->timeBeforeLay = $this->henhouse->randomBetween($this->henhouse->sim->getWorld()->getHenToLay());
        $this->henhouse->livingBeings->add($this);
    }

    public function tick(): void
    {
        // Timer before laying an egg
        $this->timeBeforeLay--;

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

    public function getFertilized(): bool
    {
        return $this->fertilized;
    }

    public function fertilize(): void
    {
        $this->fertilized = true;
    }
}
