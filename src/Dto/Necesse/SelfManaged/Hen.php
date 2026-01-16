<?php

declare(strict_types=1);

namespace App\Dto\Necesse\SelfManaged;

use App\Entity\Necesse\Sim;
use App\Enum\Necesse\SexEnum;
use App\Enum\Necesse\TypeEnum;
use Random\Randomizer;

class Hen extends LivingBeing
{
    private int $timeBeforeLay;
    private bool $fertilized;

    public function __construct(Sim $sim, Randomizer $randomizer, Henhouse $henhouse)
    {
        $this->sex = $this->randomProba($this->sim->getWorld()->getEggToFemale()) ? SexEnum::Female : SexEnum::Male;
        $this->fertilized = false;
        $this->timeBeforeLay = $this->randomBetween($this->sim->getWorld()->getHenToLay());

        return parent::__construct($sim, $randomizer, $henhouse);
    }

    public function tick(): void
    {
        --$this->timeBeforeLay;
        if (0 === $this->timeBeforeLay) {
            new Egg($this->sim, $this->randomizer, $this->henhouse, $this->fertilized);
            $this->timeBeforeLay = $this->randomBetween($this->sim->getWorld()->getHenToLay());
        }
    }

    public function getSex(): SexEnum
    {
        return SexEnum::Female;
    }

    public function getType(): TypeEnum
    {
        return TypeEnum::Hen;
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
