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
        $this->timeBeforeFertilize = 1;
        $this->henhouse->livingBeings->add($this);
    }

    public function tick(): void
    {
        $this->timeBeforeFertilize--;
        if (0 === $this->timeBeforeFertilize) {
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
