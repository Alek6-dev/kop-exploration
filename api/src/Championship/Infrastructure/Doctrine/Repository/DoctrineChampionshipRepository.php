<?php

declare(strict_types=1);

namespace App\Championship\Infrastructure\Doctrine\Repository;

use App\Bid\Infrastructure\Doctrine\Repository\DoctrineBettingRoundRepository;
use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Championship\Domain\Repository\ChampionshipRepositoryInterface;
use App\Championship\Infrastructure\Doctrine\Entity\Championship;
use App\Player\Infrastructure\Doctrine\Entity\Player;
use App\Player\Infrastructure\Doctrine\Repository\DoctrinePlayerRepository;
use App\Season\Infrastructure\Doctrine\Repository\DoctrineSeasonRaceRepository;
use App\Season\Infrastructure\Doctrine\Repository\DoctrineSeasonRepository;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use App\User\Domain\Model\UserVisitorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineChampionshipRepository extends DoctrineRepository implements ChampionshipRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = Championship::class;
    private const string ALIAS = 'championship';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    /**
     * @return array<string>
     */
    public function getInvitationCodes(): array
    {
        return $this->query()
            ->select(sprintf('%s.invitationCode', self::ALIAS))
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function withStatus(ChampionshipStatusEnum $status, bool $orCondition = false): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($status, $orCondition): void {
            if ($orCondition) {
                $parameterKey = 'status'.uniqid();
                $qb->orWhere(sprintf('%s.status = :%s', self::ALIAS, $parameterKey))->setParameter($parameterKey, $status);
            } else {
                $qb->andWhere(sprintf('%s.status = :status', self::ALIAS))->setParameter('status', $status);
            }
        });
    }

    public function withUser(UserVisitorInterface $user): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($user): void {
            $qb->innerJoin(sprintf('%s.players', self::ALIAS), DoctrinePlayerRepository::getAlias());
            $qb->andWhere(sprintf('%s.user = :user', DoctrinePlayerRepository::getAlias()))->setParameter('user', $user);
        });
    }

    public function withInvitationCode(string $invitationCode): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($invitationCode): void {
            $qb->andWhere(sprintf('%s.invitationCode = :invitationCode', self::ALIAS))->setParameter('invitationCode', $invitationCode);
        });
    }

    public function getByInvitationCode(string $invitationCode): ?ChampionshipInterface
    {
        return $this->withInvitationCode($invitationCode)
            ->query()
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function groupByChampionship(): static
    {
        return $this->filter(static function (QueryBuilder $qb): void {
            $qb->groupBy(sprintf('%s.id', self::ALIAS));
        });
    }

    public function withCurrentRoundEndDateLessThan(?\DateTimeImmutable $date): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($date): void {
            $qb->andWhere(sprintf('%s.currentRoundEndDate < :date', self::ALIAS))->setParameter('date', $date);
        });
    }

    /**
     * A betting round is over if the current round end date is greater than current date or if all players have a full selection or a betting round on the current round.
     */
    public function withBettingRoundOver(?\DateTimeImmutable $date): static
    {
        return $this
            ->filter(static function (QueryBuilder $qb) use ($date): void {
                $qb->innerJoin(sprintf('%s.players', self::ALIAS), DoctrinePlayerRepository::getAlias());
                $qb->leftJoin(sprintf('%s.bettingRounds', DoctrinePlayerRepository::getAlias()), DoctrineBettingRoundRepository::getAlias());
                $qb->andWhere(sprintf(
                    '%s.currentRoundEndDate < :date OR (
                            (%s.selectedTeam IS NOT NULL AND %s.selectedDriver1 IS NOT NULL AND %s.selectedDriver2 IS NOT NULL)
                                  OR (%s.id IS NOT NULL AND %s.round = %s.currentRound)
                        )',
                    self::ALIAS,
                    DoctrinePlayerRepository::getAlias(),
                    DoctrinePlayerRepository::getAlias(),
                    DoctrinePlayerRepository::getAlias(),
                    DoctrineBettingRoundRepository::getAlias(),
                    DoctrineBettingRoundRepository::getAlias(),
                    self::ALIAS,
                ))
                    ->setParameter('date', $date);
                $qb->groupBy(sprintf('%s.id', self::ALIAS));
                $qb->having(sprintf('COUNT(DISTINCT(%s.id)) = (SELECT count(p.id) FROM %s p WHERE p.championship=%s.id)', DoctrinePlayerRepository::getAlias(), Player::class, self::ALIAS));
            });
    }

    public function withPlayerSlotsAreFull(): static
    {
        return $this->filter(static function (QueryBuilder $qb): void {
            $qb->andWhere(sprintf('%s.numberOfPlayers = (SELECT count(p.id) FROM %s p WHERE p.championship=%s.id)', self::ALIAS, Player::class, DoctrineChampionshipRepository::getAlias()));
        });
    }

    public function withLimitStrategyDate(?\DateTimeImmutable $date): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($date): void {
            $qb->innerJoin(sprintf('%s.season', self::ALIAS), DoctrineSeasonRepository::getAlias());
            $qb->innerJoin(sprintf('%s.seasonRaces', DoctrineSeasonRepository::getAlias()), DoctrineSeasonRaceRepository::getAlias());
            $qb->andWhere(sprintf('%s.limitStrategyDate > :date', DoctrineSeasonRaceRepository::getAlias()))->setParameter('date', $date);
        });
    }

    public function withStatuses(array $statuses): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($statuses): void {
            $qb->andWhere(sprintf('%s.status IN (:statuses)', self::ALIAS))
                ->setParameter('statuses', $statuses);
        });
    }
}
