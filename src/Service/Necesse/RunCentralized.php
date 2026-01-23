<?php

declare(strict_types=1);

namespace App\Service\Necesse;

use App\Dto\Necesse\Centralized\Henhouse;
use App\Entity\Necesse\Bar;
use App\Entity\Necesse\Sim;
use Random\Randomizer;

class RunCentralized implements RunInterface
{
    private Henhouse $henhouse;

    public function __construct() {}

    public function start(Sim $sim, Randomizer $randomizer): void
    {
        // Create the henhouse with sim params, the randomizer
        $this->henhouse = new Henhouse($sim, $randomizer);

        // Add the starting number of hens and roosters
        $this->henhouse->initiliaze();
    }

    public function update(int $deltaTime): array
    {
        return [$this->henhouseToBar(), 1];
    }

    public function stop(): void
    {
        // nothing to do at the end
    }

    /**
     * Convert the henhouse pool and products count to a bar.
     */
    private function henhouseToBar(): Bar
    {
        $bar = new Bar();
        $bar->setProducedEgg($this->henhouse->producedEgg);
        $bar->setProducedMeat($this->henhouse->producedMeat);
        $bar->setLivingEgg($this->henhouse->eggs->active);
        $bar->setLivingChickFemale($this->henhouse->chicksFemale->active);
        $bar->setLivingChickMale($this->henhouse->chicksMale->active);
        $bar->setLivingHenFertilized($this->henhouse->hensFertilized->active);
        $bar->setLivingHenVirgo($this->henhouse->hensVirgo->active);
        $bar->setLivingRooster($this->henhouse->roosters->active);

        return $bar;
    }
}
