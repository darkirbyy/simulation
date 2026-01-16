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
        --$this->timeBeforeFertilize;
        if (0 === $this->timeBeforeFertilize) {
            $hensVirgo = $this->henhouse->livingBeings->filter(fn (LivingBeing $l) => TypeEnum::Hen === $l->getType())->filter(fn (Hen $h) => !$h->getFertilized());
            if ($hensVirgo->count() > 0) {
                $this->randomElement($hensVirgo)->fertilize();
                $this->timeBeforeFertilize = $this->randomBetween($this->sim->getWorld()->getRoosterToFertilize());
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
        return TypeEnum::Rooster;
    }
}
