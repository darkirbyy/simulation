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
        $fertilized = $args[0];
        if (!$fertilized || $this->henhouse->livingBeings->filter(fn (LivingBeing $l) => $l instanceof Egg)->count() === $this->sim->getLimitNest()) {
            ++$this->henhouse->producedEgg;
        } else {
            $this->timeBeforeHatch = $this->henhouse->randomBetween($this->sim->getWorld()->getEggToChick());
            $this->henhouse->livingBeings->add($this);
        }
    }

    public function tick(): void
    {
        --$this->timeBeforeHatch;
        if (0 === $this->timeBeforeHatch) {
            $chick = new Chick($this->sim, $this->henhouse);
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
}
