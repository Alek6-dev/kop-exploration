<?php

declare(strict_types=1);

namespace App\Driver\Infrastructure\Doctrine\Repository;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Driver\Domain\Repository\DriverRepositoryInterface;
use App\Driver\Infrastructure\Doctrine\Entity\Driver;
use App\Player\Infrastructure\Doctrine\Entity\Player;
use App\Player\Infrastructure\Doctrine\Repository\DoctrinePlayerRepository;
use App\Season\Domain\Model\SeasonInterface;
use App\Season\Infrastructure\Doctrine\Repository\DoctrineSeasonTeamRepository;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use App\Team\Infrastructure\Doctrine\Repository\DoctrineTeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

class DoctrineDriverRepository extends DoctrineRepository implements DriverRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = Driver::class;
    private const string ALIAS = 'driver';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
    }

    public function getAll(): ?array
    {
        return $this->query()->getQuery()->getResult();
    }

    public function withSeason(SeasonInterface $season): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($season): void {
            $qb->innerJoin(sprintf('%s.team', self::ALIAS), DoctrineTeamRepository::getAlias());
            $qb->innerJoin(sprintf('%s.seasonTeams', DoctrineTeamRepository::getAlias()), DoctrineSeasonTeamRepository::getAlias());
            $qb->andWhere(sprintf('%s.season = :season', DoctrineSeasonTeamRepository::getAlias()))->setParameter('season', $season);
        });
    }

    public function withIsReplacement(bool $isReplacement): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($isReplacement): void {
            $qb
                ->andWhere(sprintf('%s.isReplacement = :isReplacement', self::ALIAS))
                ->setParameter('isReplacement', $isReplacement)
            ;
        });
    }

    public function withIsReplacedPermanently(bool $isReplacedPermanently): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($isReplacedPermanently): void {
            $qb
                ->andWhere(sprintf('%s.isReplacedPermanently = :isReplacedPermanently', self::ALIAS))
                ->setParameter('isReplacedPermanently', $isReplacedPermanently)
            ;
        });
    }

    public function withReplacementPermanently(): static
    {
        return $this->filter(function (QueryBuilder $qb): void {
            $qb->andWhere(
                $qb->expr()->orX(
                    sprintf('%s.replacedPermanently = false', self::ALIAS),
                    $qb->expr()->in(
                        sprintf('%s.id', self::ALIAS),
                        $this->em->createQueryBuilder()
                            ->select('IDENTITY(d2.replacedBy)')
                            ->from(Driver::class, 'd2')
                            ->where('d2.replacedPermanently = true')
                            ->getDQL()
                    )
                ));
        });
    }

    public function withOrderByMinValue(string $direction = 'DESC'): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($direction): void {
            $qb->orderBy(sprintf('%s.minValue', self::ALIAS), $direction);
        });
    }

    public function withNotAlreadySelected(ChampionshipInterface $championship): static
    {
        return $this->withSeason($championship->getSeason())
            ->filter(static function (QueryBuilder $qb) use ($championship): void {
                $qb->leftJoin(
                    Player::class,
                    DoctrinePlayerRepository::getAlias(),
                    Join::WITH,
                    sprintf('(%s.selectedDriver1=%s.id OR %s.selectedDriver2=%s.id) AND %s.championship=:championship', DoctrinePlayerRepository::getAlias(), self::ALIAS, DoctrinePlayerRepository::getAlias(), self::ALIAS, DoctrinePlayerRepository::getAlias()))
                    ->setParameter('championship', $championship)
                ;
                $qb->andWhere(sprintf('%s.uuid IS NULL', DoctrinePlayerRepository::getAlias()));
            });
    }

    public function withFullName(string $fullName): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($fullName): void {
            $qb->andWhere(sprintf("CONCAT(%s.firstName, ' ', %s.lastName) = :fullName", self::ALIAS, self::ALIAS))->setParameter('fullName', $fullName);
        });
    }
}
