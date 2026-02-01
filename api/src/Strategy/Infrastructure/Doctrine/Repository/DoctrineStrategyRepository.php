<?php

declare(strict_types=1);

namespace App\Strategy\Infrastructure\Doctrine\Repository;

use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Infrastructure\Doctrine\Entity\ChampionshipRace;
use App\Championship\Infrastructure\Doctrine\Repository\DoctrineChampionshipRaceRepository;
use App\Championship\Infrastructure\Doctrine\Repository\DoctrineChampionshipRepository;
use App\Player\Domain\Model\PlayerInterface;
use App\Player\Infrastructure\Doctrine\Repository\DoctrinePlayerRepository;
use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Season\Infrastructure\Doctrine\Entity\SeasonRace;
use App\Season\Infrastructure\Doctrine\Repository\DoctrineSeasonRaceRepository;
use App\Season\Infrastructure\Doctrine\Repository\DoctrineSeasonRepository;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Enum\ComparisonEnum;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use App\Strategy\Domain\Repository\StrategyRepositoryInterface;
use App\Strategy\Infrastructure\Doctrine\Entity\Strategy;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineStrategyRepository extends DoctrineRepository implements StrategyRepositoryInterface
{
    use CrudRepositoryTrait;

    private const string ENTITY_CLASS = Strategy::class;
    private const string ALIAS = 'strategy';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function withSeason(SeasonInterface $season): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($season): void {
            $qb->innerJoin(sprintf('%s.championship', self::ALIAS), DoctrineChampionshipRepository::getAlias())
                ->andWhere(sprintf('%s.season = :season', DoctrineChampionshipRepository::getAlias()))->setParameter('season', $season);
        });
    }

    public function withRace(RaceInterface $race): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($race): void {
            $qb->andWhere(sprintf('%s.race = :race', self::ALIAS))->setParameter('race', $race);
        });
    }

    public function withPlayer(PlayerInterface $player): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($player): void {
            $qb->andWhere(sprintf('%s.player = :player', self::ALIAS))->setParameter('player', $player);
        });
    }

    public function withStatus(ChampionshipRaceStatusEnum $status): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($status): void {
            $qb->innerJoin(
                ChampionshipRace::class,
                DoctrineChampionshipRaceRepository::getAlias(),
                sprintf('%s.race = %s.race', self::ALIAS, DoctrineChampionshipRaceRepository::getAlias()),
            );
            $qb->andWhere(sprintf('%s.status = :status', DoctrineChampionshipRaceRepository::getAlias()))->setParameter('status', $status);
        });
    }

    public function withSeasonIsActive(): static
    {
        return $this->filter(static function (QueryBuilder $qb): void {
            $qb->innerJoin(sprintf('%s.season', self::ALIAS), DoctrineSeasonRepository::getAlias());
            $qb->andWhere(sprintf('%s.isActive = 1', DoctrineSeasonRepository::getAlias()));
        });
    }

    public function withLimitStrategyDate(\DateTimeImmutable $date): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($date): void {
            $qb->innerJoin(
                SeasonRace::class,
                DoctrineSeasonRaceRepository::getAlias(),
                sprintf('%s.race = %s.race AND %s.season = %s.season', self::ALIAS, DoctrineSeasonRaceRepository::getAlias(), self::ALIAS, DoctrineSeasonRaceRepository::getAlias()),
            );
            $qb->andWhere(sprintf('%s.date = :date', DoctrineSeasonRaceRepository::getAlias()))
                ->setParameter('date', $date);
        });
    }

    public function withChampionship(ChampionshipInterface $championship): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($championship): void {
            $qb->andWhere(sprintf('%s.championship = :championship', self::ALIAS))->setParameter('championship', $championship);
        });
    }

    public function withOrderByPosition(string $direction = 'DESC'): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($direction): void {
            $qb->addOrderBy(sprintf('%s.position', self::ALIAS), $direction);
        });
    }

    public function withOrderByScore(string $direction = 'DESC'): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($direction): void {
            $qb->addOrderBy(sprintf('%s.score', self::ALIAS), $direction);
        });
    }

    public function withPosition(int $position, ComparisonEnum $comparison = ComparisonEnum::EQUAL): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($position, $comparison): void {
            $qb->andWhere(sprintf('%s.position %s :position', self::ALIAS, $comparison->value))
                ->setParameter('position', $position)
            ;
        });
    }

    public function withUser(UserVisitorInterface $user): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($user): void {
            $qb->leftJoin(sprintf('%s.player', self::ALIAS), DoctrinePlayerRepository::getAlias())
                ->andWhere(sprintf('%s.user = :user', DoctrinePlayerRepository::getAlias()))
                ->setParameter('user', $user)
            ;
        });
    }
}
