<?php

declare(strict_types=1);

namespace App\Service\Necesse;

use App\Entity\Necesse\Bar;
use App\Entity\Necesse\Run;

class RunManager
{
    public function __construct(private SimInterface $sim)
    {
    }

    public function calculateBars(Run $run): void
    {
        $run->setDate(new \DateTime());
        srand($run->getSeed());
        $startTime = microtime(true);

        $bar = $this->sim->start($run);
        $run->addBar($bar);
        $previousBar = $bar;

        for ($time = 1; $time <= $run->getTime(); ++$time) {
            $bar = $this->sim->update($time);
            if (!$this->areBarsEqual($bar, $previousBar) || $time === $run->getTime()) {
                $run->addBar($bar);
                $previousBar = $bar;
            }
        }

        $this->sim->stop();

        $stopTime = microtime(true);
        $run->setDuration($stopTime - $startTime);
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
