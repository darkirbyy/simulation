<?php

declare(strict_types=1);

namespace App\Service\Necesse;

use App\Entity\Necesse\Bar;
use App\Entity\Necesse\Sim;

class SimManager
{
    public function calculateBars(Sim $sim): void
    {
        $run = new RunTest();

        $sim->setDate(new \DateTime());
        srand($sim->getSeed());
        $startTime = microtime(true);

        $bar = $run->start($sim);
        $sim->addBar($bar);
        $previousBar = $bar;

        for ($time = 1; $time <= $sim->getTime(); ++$time) {
            $bar = $run->update($time);
            if (!$this->areBarsEqual($bar, $previousBar) || $time === $sim->getTime()) {
                $sim->addBar($bar);
                $previousBar = $bar;
            }
        }

        $run->stop();

        $stopTime = microtime(true);
        $sim->setDuration($stopTime - $startTime);
    }

    public function areBarsEqual(Bar $bar1, Bar $bar2): bool
    {
        return $bar1->getProducedEgg() == $bar2->getProducedEgg()
            && $bar1->getProducedMeat() == $bar2->getProducedMeat()
            && $bar1->getLivingEgg() == $bar2->getLivingEgg()
            && $bar1->getLivingChickFemale() == $bar2->getLivingChickFemale()
            && $bar1->getLivingChickMale() == $bar2->getLivingChickMale()
            && $bar1->getLivingHenFertilized() == $bar2->getLivingHenFertilized()
            && $bar1->getLivingHenVirgo() == $bar2->getLivingHenVirgo()
            && $bar1->getLivingRooster() == $bar2->getLivingRooster();
    }
}
