<?php

declare(strict_types=1);

namespace App\Service\Necesse;

use App\Entity\Necesse\Bar;
use App\Entity\Necesse\Sim;
use Random\Randomizer;

class RunCentralized implements RunInterface
{
    public function __construct() {}

    public function start(Sim $sim, Randomizer $randomizer): void {}

    public function update(int $deltaTime): array
    {
        $bar = new Bar();

        return [$bar, 1800];
    }

    public function stop(): void {}
}
