<?php

declare(strict_types=1);

namespace App\Dto\Necesse\SelfManaged;

use Doctrine\Common\Collections\ArrayCollection;

class Henhouse
{
    public function __construct(public ArrayCollection $livingBeings, public int $producedEgg, public int $producedMeat)
    {
    }
}
