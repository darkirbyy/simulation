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
        $this->sex = $this->henhouse->randomProba($this->henhouse->sim->getWorld()->getEggToFemale()) ? SexEnum::Female : SexEnum::Male;
        $this->fertilized = false;
        $this->timeBeforeLay = $this->henhouse->randomBetween($this->henhouse->sim->getWorld()->getHenToLay());
        $this->henhouse->livingBeings->add($this);
    }

    public function tick(): void
    {
        $this->timeBeforeLay--;
        if (0 === $this->timeBeforeLay) {
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
