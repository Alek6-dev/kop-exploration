<?php

declare(strict_types=1);

namespace App\Player\Infrastructure\Doctrine\Repository;

use App\Bid\Infrastructure\Doctrine\Repository\DoctrineBettingRoundRepository;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Infrastructure\Doctrine\Repository\DoctrineChampionshipRepository;
use App\Driver\Domain\Model\DriverInterface;
use App\Player\Domain\Repository\PlayerRepositoryInterface;
use App\Player\Infrastructure\Doctrine\Entity\Player;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Enum\ComparisonEnum;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use App\Team\Domain\Model\TeamInterface;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

class DoctrinePlayerRepository extends DoctrineRepository implements PlayerRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = Player::class;
    private const string ALIAS = 'player';

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

    public function withDriver(DriverInterface $driver): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($driver): void {
            $qb->andWhere(
                sprintf('%s.driver1 = :driver OR %s.driver2 = :driver', self::ALIAS, self::ALIAS))->setParameter('driver', $driver);
        });
    }

    public function withTeam(TeamInterface $team): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($team): void {
            $qb->andWhere(sprintf('%s.team = :team', self::ALIAS))->setParameter('team', $team);
        });
    }

    public function withNoCompleteSelection(): static
    {
        return $this->filter(static function (QueryBuilder $qb): void {
            $qb->andWhere(sprintf(
                '%s.selectedDriver1 IS NULL OR %s.selectedDriver2 IS NULL OR %s.selectedTeam IS NULL',
                self::ALIAS,
                self::ALIAS,
                self::ALIAS,
            ));
        });
    }

    public function orderByRemainingBudget(string $direction = 'DESC'): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($direction): void {
            $qb->addOrderBy(sprintf('%s.remainingBudget', self::ALIAS), $direction);
        });
    }

    public function orderByCreatedAt(string $direction = 'DESC'): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($direction): void {
            $qb->addOrderBy(sprintf('%s.createdAt', self::ALIAS), $direction);
        });
    }

    public function orderByBettingRoundCreatedAt(int $round, string $direction = 'DESC'): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($round, $direction): void {
            $qb->leftJoin(
                sprintf('%s.bettingRounds', self::ALIAS),
                DoctrineBettingRoundRepository::getAlias(),
                Join::WITH,
                sprintf('%s.round=:round', DoctrineBettingRoundRepository::getAlias())
            )->setParameter('round', $round);
            $qb->addOrderBy(sprintf('%s.createdAt', DoctrineBettingRoundRepository::getAlias()), $direction);
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

    public function withChampionshipStatus(ChampionshipStatusEnum $statusEnum): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($statusEnum): void {
            $qb->leftJoin(sprintf('%s.championship', self::ALIAS), DoctrineChampionshipRepository::getAlias())
                ->andWhere(sprintf('%s.status=:status', DoctrineChampionshipRepository::getAlias()))
                ->setParameter('status', $statusEnum);
        });
    }

    public function withUser(UserVisitorInterface $user): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($user): void {
            $qb->andWhere(sprintf('%s.user = :user', DoctrinePlayerRepository::getAlias()))
                ->setParameter('user', $user)
            ;
        });
    }
}
