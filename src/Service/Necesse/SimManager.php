<?php

declare(strict_types=1);

namespace App\Service\Necesse;

use App\Entity\Necesse\Bar;
use App\Entity\Necesse\Sim;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;

class SimManager
{
    public function __construct(private RunSelfManaged $run)
    {
    }

    public function calculateBars(Sim $sim): void
    {
        $sim->setDate(new \DateTime());
        $memoryBefore = memory_get_usage();
        $startTime = microtime(true);

        $randomizer = new Randomizer(new Xoshiro256StarStar($sim->getSeed()));
        $bar = $this->run->start($sim, $randomizer);
        $sim->addBar($bar);
        $previousBar = $bar;

        for ($time = 1; $time <= $sim->getTime(); ++$time) {
            $bar = $this->run->update($time);
            if (!$this->areBarsEqual($bar, $previousBar) || $time === $sim->getTime()) {
                $sim->addBar($bar);
                $previousBar = $bar;
            }
        }

        $this->run->stop();

        $stopTime = microtime(true);
        $memoryAfter = memory_get_usage();
        $sim->setDuration($stopTime - $startTime);
        $sim->setMemory($memoryAfter - $memoryBefore);
    }

    private function areBarsEqual(Bar $bar1, Bar $bar2): bool
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
