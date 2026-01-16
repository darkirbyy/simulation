<?php

declare(strict_types=1);

namespace App\Service\Necesse;

use App\Entity\Necesse\Bar;
use App\Entity\Necesse\Sim;
use Random\Randomizer;

interface RunInterface
{
    /**
     * Called at the start of the simulation, must return the initial bar.
     */
    public function start(Sim $sim, Randomizer $randomizer): Bar;

    /**
     * Called at every step of the simulation, must return the bar at time $time.
     */
    public function update(int $time): Bar;

    /**
     * Called at the end of the simulation.
     */
    public function stop(): void;
}
