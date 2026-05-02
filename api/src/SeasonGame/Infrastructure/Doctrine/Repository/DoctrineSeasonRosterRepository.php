<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\Doctrine\Repository;

use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonRoster;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineSeasonRosterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeasonRoster::class);
    }
}
