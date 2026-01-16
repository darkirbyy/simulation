<?php

declare(strict_types=1);

namespace App\Dto\Necesse\SelfManaged;

use App\Enum\Necesse\ReplaceModeEnum;
use App\Enum\Necesse\SexEnum;
use App\Enum\Necesse\TypeEnum;

class Chick extends LivingBeing
{
    private int $timeBeforeAdult;
    private SexEnum $sex;

    public function initialize(...$args): void
    {
        $this->sex = $this->henhouse->randomProba($this->henhouse->sim->getWorld()->getEggToFemale()) ? SexEnum::Female : SexEnum::Male;
        $this->timeBeforeAdult = $this->henhouse->randomBetween($this->henhouse->sim->getWorld()->getChickToChicken());

        $chickensOfSameSex = $this->henhouse->livingBeings->filter(fn (LivingBeing $l) => $l->getSex() === $this->sex && TypeEnum::Chicken === $l->getType());
        if ($chickensOfSameSex->count() === (SexEnum::Female == $this->sex ? $this->henhouse->sim->getLimitHen() : $this->henhouse->sim->getLimitRooster())) {
            if (ReplaceModeEnum::Random == $this->henhouse->sim->getWorld()->getReplaceMode()) {
                $chickenToRemove = $this->henhouse->randomElement($chickensOfSameSex);
            } else {
                // todo : false
                $maxTimer = max(...(clone $chickensOfSameSex)->map(fn (LivingBeing $l) => $l->getTimer())->toArray());
                $chickenToRemove = $chickensOfSameSex->findFirst(fn (LivingBeing $l) => $l->getTimer() == $maxTimer);
            }
            $this->henhouse->producedMeat++;
            $this->henhouse->livingBeings->removeElement($chickenToRemove);
        }
        $this->henhouse->livingBeings->add($this);
    }

    public function tick(): void
    {
        $this->timeBeforeAdult--;
        if (0 === $this->timeBeforeAdult) {
            $this->henhouse->livingBeings->removeElement($this);
            if (SexEnum::Female == $this->sex) {
                $hen = new Hen($this->henhouse);
                $hen->initialize();
            } else {
                $rooster = new Rooster($this->henhouse);
                $rooster->initialize();
            }
        }
    }

    public function getSex(): SexEnum
    {
        return $this->sex;
    }

    public function getType(): TypeEnum
    {
        return TypeEnum::Chick;
    }

    public function getTimer(): int
    {
        return $this->timeBeforeAdult;
    }
}
