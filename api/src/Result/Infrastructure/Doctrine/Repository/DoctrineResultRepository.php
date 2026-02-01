<?php

declare(strict_types=1);

namespace App\Result\Infrastructure\Doctrine\Repository;

use App\Race\Domain\Model\RaceInterface;
use App\Result\Domain\Repository\ResultRepositoryInterface;
use App\Result\Infrastructure\Doctrine\Entity\Result;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineResultRepository extends DoctrineRepository implements ResultRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = Result::class;
    private const string ALIAS = 'result';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function removeManyBySeasonRaceType(SeasonInterface $season, RaceInterface $race): void
    {
        $this->query()
            ->delete()
            ->andWhere(sprintf('%s.season = :season', self::ALIAS))
            ->andWhere(sprintf('%s.race = :race', self::ALIAS))
            ->setParameter('season', $season)
            ->setParameter('race', $race)
            ->getQuery()
            ->getResult()
        ;
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
            $qb->where(sprintf('%s.race = :race', self::ALIAS))->setParameter('race', $race);
        });
    }
}
