<?php

declare(strict_types=1);

namespace App\Bonus\Infrastructure\Doctrine\Repository;

use App\Bonus\Domain\Repository\BonusApplicationRepositoryInterface;
use App\Bonus\Infrastructure\Doctrine\Entity\BonusApplication;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Duel\Domain\Model\DuelInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use App\Strategy\Domain\Model\StrategyInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineBonusApplicationRepository extends DoctrineRepository implements BonusApplicationRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = BonusApplication::class;
    private const string ALIAS = 'bonus_application';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function withPlayer(PlayerInterface $player): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($player): void {
            $qb->andWhere(sprintf('%s.player = :player', self::ALIAS))
                ->setParameter('player', $player)
            ;
        });
    }

    public function withStrategy(StrategyInterface $strategy): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($strategy): void {
            $qb->andWhere(sprintf('%s.strategy = :strategy', self::ALIAS))
                ->setParameter('strategy', $strategy)
            ;
        });
    }

    public function withDuel(DuelInterface $duel): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($duel): void {
            $qb->andWhere(sprintf('%s.duel = :duel', self::ALIAS))
                ->setParameter('duel', $duel)
            ;
        });
    }

    public function withStrategyIsNull(): static
    {
        return $this->filter(static function (QueryBuilder $qb): void {
            $qb->andWhere(sprintf('%s.strategy IS NULL', self::ALIAS));
        });
    }

    public function withDuelIsNull(): static
    {
        return $this->filter(static function (QueryBuilder $qb): void {
            $qb->andWhere(sprintf('%s.duel IS NULL', self::ALIAS));
        });
    }

    public function withTarget(PlayerInterface $player): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($player): void {
            $qb->andWhere(sprintf('%s.target = :target', self::ALIAS))
                ->setParameter('target', $player)
            ;
        });
    }

    public function withChampionship(ChampionshipInterface $championship): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($championship): void {
            $qb->andWhere(sprintf('%s.championship = :championship', self::ALIAS))
                ->setParameter('championship', $championship)
            ;
        });
    }

    public function withRace(RaceInterface $race): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($race): void {
            $qb->andWhere(sprintf('%s.race = :race', self::ALIAS))
                ->setParameter('race', $race)
            ;
        });
    }

    public function withOrderBySort(string $direction = 'ASC'): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($direction): void {
            $qb
                ->leftJoin(sprintf('%s.bonus', self::ALIAS), DoctrineBonusRepository::getAlias())
                ->addOrderBy(sprintf('%s.sort', DoctrineBonusRepository::getAlias()), $direction)
                ->addOrderBy(sprintf('%s.bonus', self::ALIAS), 'DESC')
            ;
        });
    }

    public function withOrderByCreatedAt(string $direction = 'DESC'): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($direction): void {
            $qb->addOrderBy(sprintf('%s.createdAt', self::ALIAS), $direction);
        });
    }
}
