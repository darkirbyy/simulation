<?php

declare(strict_types=1);

namespace App\Service\Necesse;

use App\Dto\Necesse\SelfManaged\Hen;
use App\Dto\Necesse\SelfManaged\Henhouse;
use App\Dto\Necesse\SelfManaged\LivingBeing;
use App\Dto\Necesse\SelfManaged\Rooster;
use App\Entity\Necesse\Bar;
use App\Entity\Necesse\Sim;
use App\Enum\Necesse\SexEnum as SE;
use App\Enum\Necesse\TypeEnum as TE;
use Doctrine\Common\Collections\ArrayCollection;
use Random\Randomizer;

class RunSelfManaged implements RunInterface
{
    private Henhouse $henhouse;

    public function __construct()
    {
    }

    public function start(Sim $sim, Randomizer $randomizer): Bar
    {
        $this->henhouse = new Henhouse($sim, $randomizer, new ArrayCollection(), 0, 0);

        for ($initialHen = 0; $initialHen < $sim->getInitialHen(); $initialHen++) {
            $hen = new Hen($this->henhouse);
            $hen->initialize();
        }
        for ($initialRooster = 0; $initialRooster < $sim->getInitialRooster(); $initialRooster++) {
            $rooster = new Rooster($this->henhouse);
            $rooster->initialize();
        }

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
        $bar->setLivingEgg($this->henhouse->livingBeings->filter(fn (LivingBeing $l) => TE::Egg == $l->getType())->count());
        $bar->setLivingChickFemale($this->henhouse->livingBeings->filter(fn (LivingBeing $l) => TE::Chick == $l->getType() && SE::Female == $l->getSex())->count());
        $bar->setLivingChickMale($this->henhouse->livingBeings->filter(fn (LivingBeing $l) => TE::Chick == $l->getType() && SE::Male == $l->getSex())->count());
        $bar->setLivingHenFertilized($this->henhouse->livingBeings->filter(fn (LivingBeing $l) => TE::Hen == $l->getType())->filter(fn (Hen $h) => $h->getFertilized())->count());
        $bar->setLivingHenVirgo($this->henhouse->livingBeings->filter(fn (LivingBeing $l) => TE::Hen == $l->getType())->filter(fn (Hen $h) => !$h->getFertilized())->count());
        $bar->setLivingRooster($this->henhouse->livingBeings->filter(fn (LivingBeing $l) => TE::Rooster == $l->getType())->count());

        return $bar;
    }
}
