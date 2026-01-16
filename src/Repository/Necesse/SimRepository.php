<?php

declare(strict_types=1);

namespace App\Repository\Necesse;

use App\Entity\Necesse\Sim;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SimRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sim::class);
    }
}
