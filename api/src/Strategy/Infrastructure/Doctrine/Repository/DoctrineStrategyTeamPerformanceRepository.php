<?php

declare(strict_types=1);

namespace App\Strategy\Infrastructure\Doctrine\Repository;

use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use App\Strategy\Domain\Repository\StrategyDriverPerformanceRepositoryInterface;
use App\Strategy\Infrastructure\Doctrine\Entity\StrategyDriverPerformance;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineStrategyTeamPerformanceRepository extends DoctrineRepository implements StrategyDriverPerformanceRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = StrategyDriverPerformance::class;
    private const string ALIAS = 'strategy_driver_performance';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }
}
