<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\Doctrine\Repository;

use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Domain\Repository\ChampionshipRaceRepositoryInterface;
use App\Championship\Infrastructure\Doctrine\Entity\ChampionshipRace;
use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Season\Infrastructure\Doctrine\Entity\SeasonRace;
use App\Season\Infrastructure\Doctrine\Repository\DoctrineSeasonRaceRepository;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineChampionshipRaceRepository extends DoctrineRepository implements ChampionshipRaceRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = ChampionshipRace::class;
    private const string ALIAS = 'championship_race';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function withChampionship(ChampionshipInterface $championship): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($championship): void {
            $qb->andWhere(sprintf('%s.championship = :championship', self::ALIAS))->setParameter('championship', $championship);
        });
    }

    public function withSeason(SeasonInterface $season): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($season): void {
            $qb->innerJoin(sprintf('%s.championship', self::ALIAS), DoctrineChampionshipRepository::getAlias());
            $qb->andWhere(sprintf('%s.season = :season', DoctrineChampionshipRepository::getAlias()))->setParameter('season', $season);
        });
    }

    public function withRace(RaceInterface $race): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($race): void {
            $qb->andWhere(sprintf('%s.race = :race', self::ALIAS))->setParameter('race', $race);
        });
    }

    public function withStatus(ChampionshipRaceStatusEnum $status): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($status): void {
            $qb->andWhere(sprintf('%s.status = :status', self::ALIAS))->setParameter('status', $status);
        });
    }

    public function withLimitStrategyDate(\DateTimeImmutable $date): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($date): void {
            $qb->innerJoin(sprintf('%s.championship', self::ALIAS), DoctrineChampionshipRepository::getAlias());
            $qb->innerJoin(
                SeasonRace::class,
                DoctrineSeasonRaceRepository::getAlias(),
                sprintf('%s.race = %s.race AND %s.season = %s.season', self::ALIAS, DoctrineSeasonRaceRepository::getAlias(), DoctrineChampionshipRepository::getAlias(), DoctrineSeasonRaceRepository::getAlias()),
            );
            $qb->andWhere(sprintf('%s.date = :date', DoctrineSeasonRaceRepository::getAlias()))
                ->setParameter('date', $date);
        });
    }
}
