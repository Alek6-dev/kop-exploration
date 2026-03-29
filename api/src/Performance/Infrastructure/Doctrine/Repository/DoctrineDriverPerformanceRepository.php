<?php

declare(strict_types=1);

namespace App\Performance\Infrastructure\Doctrine\Repository;

use App\Driver\Domain\Model\DriverInterface;
use App\Performance\Domain\Repository\DriverPerformanceRepositoryInterface;
use App\Performance\Infrastructure\Doctrine\Entity\DriverPerformance;
use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineDriverPerformanceRepository extends DoctrineRepository implements DriverPerformanceRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = DriverPerformance::class;
    private const string ALIAS = 'driver_performance';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function withSeason(SeasonInterface $season): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($season): void {
            $qb->andWhere(sprintf('%s.season = :season', self::ALIAS))->setParameter('season', $season);
        });
    }

    public function withRace(RaceInterface $race): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($race): void {
            $qb->andWhere(sprintf('%s.race = :race', self::ALIAS))->setParameter('race', $race);
        });
    }

    public function withDriver(DriverInterface $driver): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($driver): void {
            $qb->andWhere(sprintf('%s.driver = :driver', self::ALIAS))->setParameter('driver', $driver);
        });
    }
}
