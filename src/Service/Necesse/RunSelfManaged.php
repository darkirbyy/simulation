<?php

declare(strict_types=1);

namespace App\Service\Necesse;

use App\Dto\Necesse\SelfManaged\Hen;
use App\Dto\Necesse\SelfManaged\Henhouse;
use App\Dto\Necesse\SelfManaged\LivingBeing;
use App\Entity\Necesse\Bar;
use App\Entity\Necesse\Sim;
use App\Enum\Necesse\SexEnum;
use App\Enum\Necesse\TypeEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

class RunSelfManaged implements RunInterface
{
    private Henhouse $henhouse;

    public function __construct()
    {
    }

    public function start(Sim $sim): Bar
    {
        $this->henhouse = new Henhouse(new ArrayCollection(), 0, 0);
        new Hen($sim, new Randomizer(new Xoshiro256StarStar($sim->getSeed())), $this->henhouse);

        return $this->henhouseToBar(0);
    }

    public function update(int $time): Bar
    {
        foreach ($this->henhouse->livingBeings as $livingBeing) {
            $livingBeing->tick();
        }

        return $this->henhouseToBar($time);
    }

    public function stop(): void
    {
    }

    private function henhouseToBar(int $time): Bar
    {
        $bar = new Bar();
        $bar->setTime($time);
        $bar->setProducedEgg($this->henhouse->producedEgg);
        $bar->setProducedMeat($this->henhouse->producedMeat);
        $bar->setLivingEgg($this->henhouse->livingBeings->filter(fn (LivingBeing $l) => TypeEnum::Egg == $l->getType())->count());
        $bar->setLivingChickFemale($this->livingBeings->filter(fn (LivingBeing $l) => TypeEnum::Chick == $l->getType() && SexEnum::Female == $l->getSex())->count());
        $bar->setLivingChickMale($this->livingBeings->filter(fn (LivingBeing $l) => TypeEnum::Chick == $l->getType() && SexEnum::Male == $l->getSex())->count());
        $bar->setLivingHenFertilized($this->livingBeings->filter(fn (LivingBeing $l) => TypeEnum::Hen == $l->getType())->filter(fn (Hen $h) => $h->getFertilized())->count());
        $bar->setLivingHenVirgo($this->livingBeings->filter(fn (LivingBeing $l) => TypeEnum::Hen == $l->getType())->filter(fn (Hen $h) => !$h->getFertilized())->count());
        $bar->setLivingRooster($this->livingBeings->filter(fn (LivingBeing $l) => TypeEnum::Rooster == $l->getType())->count());

        return $bar;
    }
}
