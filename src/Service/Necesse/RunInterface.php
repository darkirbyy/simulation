<?php

declare(strict_types=1);

namespace App\Service\Necesse;

use App\Entity\Necesse\Sim;
use Random\Randomizer;

interface RunInterface
{
    /**
     * Called at the start of the simulation.
     */
    public function start(Sim $sim, Randomizer $randomizer): void;

    /**
     * Called at every step of the simulation, must return an array in order with
     * - (Bar) the bar $deltaTime step after the previous one
     * - (int) how many step to jump in the simulation.
     */
    public function update(int $deltaTime): array;

    /**
     * Called at the end of the simulation.
     */
    public function stop(): void;
}
