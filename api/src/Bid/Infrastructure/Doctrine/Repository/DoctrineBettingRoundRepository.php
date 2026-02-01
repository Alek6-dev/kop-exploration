<?php

declare(strict_types=1);

namespace App\Bid\Infrastructure\Doctrine\Repository;

use App\Bid\Domain\Repository\BettingRoundRepositoryInterface;
use App\Bid\Infrastructure\Doctrine\Entity\BettingRound;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Player\Infrastructure\Doctrine\Repository\DoctrinePlayerRepository;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineBettingRoundRepository extends DoctrineRepository implements BettingRoundRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = BettingRound::class;
    private const string ALIAS = 'betting_round';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function withRound(int $round): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($round): void {
            $qb->andWhere(sprintf('%s.round = :round', self::ALIAS))->setParameter('round', $round);
        });
    }

    public function withChampionship(ChampionshipInterface $championship): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($championship): void {
            $qb->innerJoin(sprintf('%s.player', self::ALIAS), DoctrinePlayerRepository::getAlias());
            $qb->andWhere(sprintf('%s.championship = :championship', DoctrinePlayerRepository::getAlias()))->setParameter('championship', $championship);
        });
    }

    public function withIsSetBySystem(bool $isSetBySystem): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($isSetBySystem): void {
            $qb->andWhere(sprintf('%s.isSetBySystem = :isSetBySystem', self::ALIAS))->setParameter('isSetBySystem', $isSetBySystem);
        });
    }

    public function orderByCreatedAt(string $direction = 'DESC'): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($direction): void {
            $qb->addOrderBy(sprintf('%s.createdAt', self::ALIAS), $direction);
        });
    }
}
