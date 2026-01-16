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
    }

    public function tick(): void
    {
        // Timer before fertilizing another hen
        $this->timeBeforeFertilize--;

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
