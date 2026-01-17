<?php

declare(strict_types=1);

namespace App\Service\Necesse;

use App\Entity\Necesse\Bar;
use App\Entity\Necesse\Sim;
use Random\Randomizer;

class RunTest implements RunInterface
{
    private int $nbProducedEgg;

    public function __construct()
    {
    }

    public function start(Sim $sim, Randomizer $randomizer): void
    {
        $this->nbProducedEgg = 0;
    }

    public function update(int $deltaTime): array
    {
        $this->nbProducedEgg += 2;

        $bar = new Bar();

        $bar->setProducedEgg($this->nbProducedEgg);
        $bar->setProducedMeat(0);
        $bar->setLivingEgg(0);
        $bar->setLivingChickFemale(0);
        $bar->setLivingChickMale(0);
        $bar->setLivingHenFertilized(0);
        $bar->setLivingHenVirgo(0);
        $bar->setLivingRooster(0);

        return [$bar, 1800];
    }

    public function stop(): void
    {
    }
}
