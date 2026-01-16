<?php

declare(strict_types=1);

namespace App\Dto\Necesse\SelfManaged;

use App\Entity\Necesse\Sim;
use App\Enum\Necesse\ReplaceModeEnum;
use App\Enum\Necesse\SexEnum;
use App\Enum\Necesse\TypeEnum;
use Random\Randomizer;

class Chick extends LivingBeing
{
    private int $timeBeforeAdult;
    private SexEnum $sex;

    public function __construct(Sim $sim, Randomizer $randomizer, Henhouse $henhouse)
    {
        $this->sex = $this->randomProba($this->sim->getWorld()->getEggToFemale()) ? SexEnum::Female : SexEnum::Male;
        $this->timeBeforeAdult = $this->randomBetween($this->sim->getWorld()->getChickToChicken());

        $chickensOfSameSex = $this->henhouse->livingBeings->filter(fn (LivingBeing $l) => $l->getSex() === $this->sex);
        if ($chickensOfSameSex->count() === (SexEnum::Female == $this->sex ? $this->sim->getLimitHen() : $this->sim->getLimitRooster())) {
            if (ReplaceModeEnum::Random == $this->sim->getWorld()->getReplaceMode()) {
                $this->henhouse->livingBeings->removeElement($this->randomElement($chickensOfSameSex));
                ++$this->henhouse->producedMeat;
            } else {
                throw new \Exception('Optimal mode not implemented yet');
            }
        }
        $this->henhouse->livingBeings->add($this);

        return parent::__construct($sim, $randomizer, $henhouse);
    }

    public function tick(): void
    {
        --$this->timeBeforeAdult;
        if (0 === $this->timeBeforeAdult) {
            $this->henhouse->livingBeings->removeElement($this);
            if (SexEnum::Female == $this->sex) {
                new Hen($this->sim, $this->randomizer, $this->henhouse);
            } else {
                new Rooster($this->sim, $this->randomizer, $this->henhouse);
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
}
