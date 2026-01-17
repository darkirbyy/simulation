<?php

declare(strict_types=1);

namespace App\Dto\Necesse\SelfManaged;

use App\Enum\Necesse\SexEnum;
use App\Enum\Necesse\TypeEnum;

class Egg extends LivingBeing
{
    private int $timeBeforeHatch;

    public function initialize(...$args): void
    {
        // Fertilized is passed as an argument from the hen
        $fertilized = $args[0];

        // If not fertilized or if there is not any nest remaining, the egg is directly counted as a product
        if (!$fertilized || $this->henhouse->livingBeings->filter(fn (LivingBeing $l) => $l instanceof Egg)->count() >= $this->henhouse->sim->getLimitNest()) {
            $this->henhouse->producedEgg++;
        }
        // Else, randomize the timer for hatching and add the egg to the pool
        else {
            $this->timeBeforeHatch = $this->henhouse->randomBetween($this->henhouse->sim->getWorld()->getEggToChick());
            $this->henhouse->livingBeings->add($this);
        }
    }

    public function tick(int $deltaTime): void
    {
        // Time before hatching
        $this->timeBeforeHatch -= $deltaTime;

        if (0 === $this->timeBeforeHatch) {
            // When timer hit 0, create a new chick, add it to the pool and remove the egg from the pool
            $chick = new Chick($this->henhouse);
            $chick->initialize();
            $this->henhouse->livingBeings->removeElement($this);
        }
    }

    public function getSex(): SexEnum
    {
        return SexEnum::Undetermined;
    }

    public function getType(): TypeEnum
    {
        return TypeEnum::Egg;
    }

    public function getTimer(): int
    {
        return $this->timeBeforeHatch;
    }
}
