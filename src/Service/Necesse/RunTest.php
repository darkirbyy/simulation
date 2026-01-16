<?php

declare(strict_types=1);

namespace App\Service\Necesse;

use App\Entity\Necesse\Bar;
use App\Entity\Necesse\Sim;

class RunTest implements RunInterface
{
    public function start(Sim $sim): Bar
    {
        $bar = new Bar();
        $bar->setTime(0);
        $bar->setProducedEgg(0);
        $bar->setProducedMeat(0);
        $bar->setLivingEgg(0);
        $bar->setLivingChickFemale(0);
        $bar->setLivingChickMale(0);
        $bar->setLivingHenFertilized(0);
        $bar->setLivingHenVirgo(0);
        $bar->setLivingRooster(0);

        return $bar;
    }

    public function update(int $time): Bar
    {
        $bar = new Bar();
        $bar->setTime($time);
        $bar->setProducedEgg(0);
        $bar->setProducedMeat(0);
        $bar->setLivingEgg(0);
        $bar->setLivingChickFemale(0);
        $bar->setLivingChickMale(0);
        $bar->setLivingHenFertilized(0);
        $bar->setLivingHenVirgo(0);
        $bar->setLivingRooster(0);

        return $bar;
    }

    public function stop(): void
    {
    }
}
