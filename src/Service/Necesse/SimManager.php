<?php

declare(strict_types=1);

namespace App\Service\Necesse;

use App\Entity\Necesse\Bar;
use App\Entity\Necesse\Sim;
use Doctrine\Common\Collections\ArrayCollection;
use Psr\Container\ContainerInterface;
use Random\Engine\Xoshiro256StarStar;
use Random\Randomizer;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

class SimManager
{
    public function __construct(#[AutowireLocator([RunSelfManaged::class, RunCentralized::class])] private ContainerInterface $runs)
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
        // Choose the right run and start the random engine
        set_time_limit(300);
        $run = $this->runs->get($sim->getRun()->toClass());
        $randomizer = new Randomizer(new Xoshiro256StarStar($sim->getSeed()));

        // Initialize the run indicators
        $sim->setDate(new \DateTime());
        $memoryBefore = memory_get_usage();
        $startTime = microtime(true);
        $iteration = -1;

        // Start the runner
        $run->start($sim, $randomizer);
        $previousBar = null;
        $time = 0;
        $deltaTime = 0;

        // For each step in the simulation, update the runner and store the bar if it differs from the previous one
        while ($time <= $sim->getTime()) {
            [$bar, $deltaTime] = $run->update($deltaTime);
            if ($this->areBarsDifferent($bar, $previousBar)) {
                $bar->setTime($time);
                $sim->addBar($bar);
                $previousBar = $bar;
            }
            $iteration++;
            $time += $deltaTime;
        }

        // Stop the runner
        $run->stop();

        // Finish the indicators
        $stopTime = microtime(true);
        $memoryAfter = memory_get_usage();
        $sim->setDuration($stopTime - $startTime);
        $sim->setMemory($memoryAfter - $memoryBefore);
        $sim->setIteration($iteration);
    }

    /**
     * Recalculate the bars with a fixed number of points and uniform repartition.
     *
     * @param Sim $sim      simulation parameter
     * @param int $nbPoints how many points to interpolate
     */
    public function interpolateBars(Sim $sim, int $nbPoints): ArrayCollection
    {
        $fixedDeltaTime = $sim->getTime() / ($nbPoints - 1);
        $fixedBars = new ArrayCollection();

        $currentBar = $sim->getBars()->first();
        $nextBar = $sim->getBars()->next();

        for ($fixedTime = 0; $fixedTime <= $sim->getTime(); $fixedTime += $fixedDeltaTime) {
            while ($nextBar && $nextBar->getTime() <= $fixedTime) {
                $currentBar = $nextBar;
                $nextBar = $sim->getBars()->next();
            }
            $fixedBar = clone $currentBar;
            $fixedBar->setTime($fixedTime);
            $fixedBar->setSim(null);
            $fixedBars->add($fixedBar);
        }

        return $fixedBars;
    }

    /**
     * Check if two bars are different by checking all values but the time.
     */
    private function areBarsDifferent(Bar $bar1, ?Bar $bar2): bool
    {
        return null === $bar2
            || $bar1->getProducedEgg() != $bar2->getProducedEgg()
            || $bar1->getProducedMeat() != $bar2->getProducedMeat()
            || $bar1->getLivingEgg() != $bar2->getLivingEgg()
            || $bar1->getLivingChickFemale() != $bar2->getLivingChickFemale()
            || $bar1->getLivingChickMale() != $bar2->getLivingChickMale()
            || $bar1->getLivingHenFertilized() != $bar2->getLivingHenFertilized()
            || $bar1->getLivingHenVirgo() != $bar2->getLivingHenVirgo()
            || $bar1->getLivingRooster() != $bar2->getLivingRooster();
    }
}
