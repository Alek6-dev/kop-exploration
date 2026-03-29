<?php

declare(strict_types=1);

namespace App\Strategy\Infrastructure\Doctrine\Repository;

use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use App\Strategy\Domain\Repository\StrategyTeamPerformanceRepositoryInterface;
use App\Strategy\Infrastructure\Doctrine\Entity\StrategyTeamPerformance;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineStrategyDriverPerformanceRepository extends DoctrineRepository implements StrategyTeamPerformanceRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = StrategyTeamPerformance::class;
    private const string ALIAS = 'strategy_team_performance';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }
}
