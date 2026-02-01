<?php

declare(strict_types=1);

namespace App\Duel\Infrastructure\Doctrine\Repository;

use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Infrastructure\Doctrine\Entity\ChampionshipRace;
use App\Championship\Infrastructure\Doctrine\Repository\DoctrineChampionshipRaceRepository;
use App\Duel\Domain\Repository\DuelRepositoryInterface;
use App\Duel\Infrastructure\Doctrine\Entity\Duel;
use App\Player\Domain\Model\PlayerInterface;
use App\Player\Infrastructure\Doctrine\Repository\DoctrinePlayerRepository;
use App\Race\Domain\Model\RaceInterface;
use App\Season\Infrastructure\Doctrine\Entity\SeasonRace;
use App\Season\Infrastructure\Doctrine\Repository\DoctrineSeasonRaceRepository;
use App\Season\Infrastructure\Doctrine\Repository\DoctrineSeasonRepository;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

class DoctrineDuelRepository extends DoctrineRepository implements DuelRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = Duel::class;
    private const string ALIAS = 'duel';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function withRace(RaceInterface $race): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($race): void {
            $qb->andWhere(sprintf('%s.race = :race', self::ALIAS))
                ->setParameter('race', $race);
        });
    }

    public function withPlayer(PlayerInterface $player): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($player): void {
            $qb->andWhere(sprintf('%s.player1 = :player OR %s.player2 = :player', self::ALIAS, self::ALIAS))->setParameter('player', $player);
        });
    }

    public function withUserWin(UserVisitorInterface $user, bool $win = true): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($user, $win): void {
            $qb->leftJoin(sprintf('%s.player1', self::ALIAS), DoctrinePlayerRepository::getAlias().'1', Join::WITH, $win ? sprintf('%s.pointsPlayer1 > %s.pointsPlayer2', self::getAlias(), self::getAlias()) : null)
                ->leftJoin(sprintf('%s.player2', self::ALIAS), DoctrinePlayerRepository::getAlias().'2', Join::WITH, $win ? sprintf('%s.pointsPlayer2 > %s.pointsPlayer1', self::getAlias(), self::getAlias()) : null)
                ->andWhere(sprintf('%s.user = :user OR %s.user = :user', DoctrinePlayerRepository::getAlias().'1', DoctrinePlayerRepository::getAlias().'2'))
                ->setParameter('user', $user)
            ;
        });
    }

    public function withStatus(ChampionshipRaceStatusEnum $status): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($status): void {
            $qb->innerJoin(
                ChampionshipRace::class,
                DoctrineChampionshipRaceRepository::getAlias(),
                sprintf('%s.race = %s.race', self::ALIAS, DoctrineChampionshipRaceRepository::getAlias()),
            )
                ->andWhere(sprintf('%s.status = :status', DoctrineChampionshipRaceRepository::getAlias()))
                ->setParameter('status', $status);
        });
    }

    public function withSeasonIsActive(): static
    {
        return $this->filter(static function (QueryBuilder $qb): void {
            $qb->innerJoin(sprintf('%s.season', self::ALIAS), DoctrineSeasonRepository::getAlias())
                ->andWhere(sprintf('%s.isActive = 1', DoctrineSeasonRepository::getAlias()));
        });
    }

    public function withLimitStrategyDate(\DateTimeImmutable $date): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($date): void {
            $qb->innerJoin(
                SeasonRace::class,
                DoctrineSeasonRaceRepository::getAlias(),
                sprintf('%s.race = %s.race AND %s.season = %s.season', self::ALIAS, DoctrineSeasonRaceRepository::getAlias(), self::ALIAS, DoctrineSeasonRaceRepository::getAlias()),
            )
                ->andWhere(sprintf('%s.date = :date', DoctrineSeasonRaceRepository::getAlias()))
                ->setParameter('date', $date);
        });
    }

    public function withChampionship(ChampionshipInterface $championship): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($championship): void {
            $qb->andWhere(sprintf('%s.championship = :championship', self::ALIAS))
                ->setParameter(':championship', $championship);
        });
    }
}
