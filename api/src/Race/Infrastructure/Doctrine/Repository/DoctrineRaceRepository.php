<?php

declare(strict_types=1);

namespace App\Race\Infrastructure\Doctrine\Repository;

use App\Race\Domain\Repository\RaceRepositoryInterface;
use App\Race\Infrastructure\Doctrine\Entity\Race;
use App\Season\Domain\Model\SeasonInterface;
use App\Season\Infrastructure\Doctrine\Repository\DoctrineSeasonRaceRepository;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineRaceRepository extends DoctrineRepository implements RaceRepositoryInterface
{
    use CrudRepositoryTrait;

    private const string ENTITY_CLASS = Race::class;
    private const string ALIAS = 'race';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function getAll(): ?array
    {
        return $this->query()->getQuery()->getResult();
    }

    public function withLimitStrategyDateGreaterThan(SeasonInterface $season, ?\DateTimeImmutable $date): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($season, $date): void {
            $qb->innerJoin(sprintf('%s.seasonRaces', self::ALIAS), DoctrineSeasonRaceRepository::getAlias());
            $qb->andWhere(sprintf('%s.season = :season', DoctrineSeasonRaceRepository::getAlias()))->setParameter('season', $season);
            $qb->andWhere(sprintf('%s.limitStrategyDate > :date', DoctrineSeasonRaceRepository::getAlias()))->setParameter('date', $date);
        });
    }

    public function orderByDate(string $direction = 'DESC'): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($direction): void {
            $qb->addOrderBy(sprintf('%s.date', DoctrineSeasonRaceRepository::getAlias()), $direction);
        });
    }

    public function limit(int $limit): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($limit): void {
            $qb->setMaxResults($limit);
        });
    }
}
