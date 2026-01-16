<?php

declare(strict_types=1);

namespace App\Repository\Necesse;

use App\Entity\Necesse\Bar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bar::class);
    }
}
