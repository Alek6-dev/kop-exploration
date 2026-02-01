<?php

declare(strict_types=1);

namespace App\Season\Infrastructure\Doctrine\Repository;

use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Season\Domain\Repository\SeasonRaceRepositoryInterface;
use App\Season\Infrastructure\Doctrine\Entity\SeasonRace;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineSeasonRaceRepository extends DoctrineRepository implements SeasonRaceRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = SeasonRace::class;
    private const string ALIAS = 'season_race';

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

    public function withLimitStrategyDate(\DateTimeImmutable $date): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($date): void {
            $qb->andWhere(sprintf('%s.limitStrategyDate < :date', self::ALIAS))->setParameter('date', $date);
        });
    }

    #[\Override]
    public function withMaxResult(int $limit): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($limit): void {
            $qb->where(sprintf('%s.limitStrategyDate = :date', self::ALIAS))->setParameter('date', $limit);
        });
    }
}
