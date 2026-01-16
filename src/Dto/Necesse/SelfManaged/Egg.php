<?php

declare(strict_types=1);

namespace App\Dto\Necesse\SelfManaged;

use App\Entity\Necesse\Sim;
use App\Enum\Necesse\SexEnum;
use App\Enum\Necesse\TypeEnum;
use Random\Randomizer;

class Egg extends LivingBeing
{
    private int $timeBeforeHatch;

    public function __construct(Sim $sim, Randomizer $randomizer, Henhouse $henhouse, bool $fertilized)
    {
        if (!$fertilized || $this->henhouse->livingBeings->filter(fn (LivingBeing $l) => $l instanceof Egg)->count() === $this->sim->getLimitNest()) {
            ++$this->henhouse->producedEgg;
        } else {
            $this->timeBeforeHatch = $this->randomBetween($this->sim->getWorld()->getEggToChick());
            $this->henhouse->livingBeings->add($this);
        }

        return parent::__construct($sim, $randomizer, $henhouse);
    }

    public function tick(): void
    {
        --$this->timeBeforeHatch;
        if (0 === $this->timeBeforeHatch) {
            new Chick($this->sim, $this->randomizer, $this->henhouse);
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
