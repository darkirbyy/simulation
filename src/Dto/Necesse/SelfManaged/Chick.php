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
        // The sex and the timer before adultnessare randomized
        $this->sex = $this->henhouse->randomProba($this->henhouse->sim->getWorld()->getEggToFemale()) ? SexEnum::Female : SexEnum::Male;
        $this->timeBeforeAdult = $this->henhouse->randomBetween($this->henhouse->sim->getWorld()->getChickToChicken());

        // get the list of all chicks and hen/rooster from the same sex
        $chickensOrChickOfSameSex = $this->henhouse->livingBeings->filter(fn (LivingBeing $l) => $l->getSex() === $this->sex);

        // If there is not any room remaining, retrict the list to the hen/rooster and remove one (random or the one with the bigger timer) producing one meat
        if ($chickensOrChickOfSameSex->count() === (SexEnum::Female == $this->sex ? $this->henhouse->sim->getLimitHen() : $this->henhouse->sim->getLimitRooster())) {
            $chickensOfSameSex = $chickensOrChickOfSameSex->filter(fn (LivingBeing $l) => TypeEnum::Chicken == $l->getType());
            if (ReplaceModeEnum::Random == $this->henhouse->sim->getWorld()->getReplaceMode()) {
                $chickenToRemove = $this->henhouse->randomElement($chickensOfSameSex);
            } else {
                $chickenToRemove = $chickensOfSameSex->reduce(fn (?LivingBeing $max, LivingBeing $l) => null === $max || $l->getTimer() > $max->getTimer() ? $l : $max);
            }
            $this->henhouse->producedMeat++;
            $this->henhouse->livingBeings->removeElement($chickenToRemove);
        }

        // Add the chick to the pool
        $this->henhouse->livingBeings->add($this);
    }

    public function tick(): void
    {
        // Time before becoming an hen or a rooster
        $this->timeBeforeAdult--;

        if (0 === $this->timeBeforeAdult) {
            // When timer hit 0, create a new hen or rooster, add it to the pool and remove the chick from the pool
            if (SexEnum::Female == $this->sex) {
                $hen = new Hen($this->henhouse);
                $hen->initialize();
            } else {
                $rooster = new Rooster($this->henhouse);
                $rooster->initialize();
            }
            $this->henhouse->livingBeings->removeElement($this);
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
