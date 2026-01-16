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

    /**
     * Get a valide set of simulation arameters and calculate all bars usng a runner.
     * Only bars marking a change are kept.
     *
     * @param Sim $sim the simulation parameters with no bar calculated
     */
    public function calculateBars(Sim $sim): void
    {
        // Initialize the date and randomizer, and prepare the time and memory count
        $sim->setDate(new \DateTime());
        $randomizer = new Randomizer(new Xoshiro256StarStar($sim->getSeed()));
        $memoryBefore = memory_get_usage();
        $startTime = microtime(true);

        // Start the runner and store the first bar
        $bar = $this->run->start($sim, $randomizer);
        $sim->addBar($bar);
        $previousBar = $bar;

        // For each step in the simulation, update the runner and store the bar if it differs from the previous one
        for ($time = 1; $time <= $sim->getTime(); $time++) {
            $bar = $this->run->update($time);
            if ($this->areBarsDifferent($bar, $previousBar) || $time === $sim->getTime()) {
                $sim->addBar($bar);
                $previousBar = $bar;
            }
        }

        // Stop the runner
        $this->run->stop();

        // Count the time and memory and put them in the sim results
        $stopTime = microtime(true);
        $memoryAfter = memory_get_usage();
        $sim->setDuration($stopTime - $startTime);
        $sim->setMemory($memoryAfter - $memoryBefore);
    }

    /**
     * Check if two bars are different by checking all values but the time.
     */
    private function areBarsDifferent(Bar $bar1, Bar $bar2): bool
    {
        return $bar1->getProducedEgg() != $bar2->getProducedEgg()
            || $bar1->getProducedMeat() != $bar2->getProducedMeat()
            || $bar1->getLivingEgg() != $bar2->getLivingEgg()
            || $bar1->getLivingChickFemale() != $bar2->getLivingChickFemale()
            || $bar1->getLivingChickMale() != $bar2->getLivingChickMale()
            || $bar1->getLivingHenFertilized() != $bar2->getLivingHenFertilized()
            || $bar1->getLivingHenVirgo() != $bar2->getLivingHenVirgo()
            || $bar1->getLivingRooster() != $bar2->getLivingRooster();
    }
}
