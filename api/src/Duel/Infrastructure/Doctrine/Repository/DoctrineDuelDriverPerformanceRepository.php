<?php

declare(strict_types=1);

namespace App\Duel\Infrastructure\Doctrine\Repository;

use App\Duel\Domain\Repository\DuelDriverPerformanceRepositoryInterface;
use App\Duel\Infrastructure\Doctrine\Entity\DuelDriverPerformance;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineDuelDriverPerformanceRepository extends DoctrineRepository implements DuelDriverPerformanceRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = DuelDriverPerformance::class;
    private const string ALIAS = 'duel_driver_performance';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }
}
