<?php

declare(strict_types=1);

namespace App\Team\Infrastructure\Doctrine\Repository;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Player\Infrastructure\Doctrine\Entity\Player;
use App\Player\Infrastructure\Doctrine\Repository\DoctrinePlayerRepository;
use App\Season\Domain\Model\SeasonInterface;
use App\Season\Infrastructure\Doctrine\Repository\DoctrineSeasonTeamRepository;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use App\Team\Domain\Repository\TeamRepositoryInterface;
use App\Team\Infrastructure\Doctrine\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

class DoctrineTeamRepository extends DoctrineRepository implements TeamRepositoryInterface
{
    use CrudRepositoryTrait;

    private const string ENTITY_CLASS = Team::class;
    private const string ALIAS = 'team';

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
            $qb->innerJoin(sprintf('%s.seasonTeams', DoctrineTeamRepository::getAlias()), DoctrineSeasonTeamRepository::getAlias());
            $qb->andWhere(sprintf('%s.season = :season', DoctrineSeasonTeamRepository::getAlias()))->setParameter('season', $season);
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
                    sprintf('%s.selectedTeam=%s.id AND %s.championship=:championship', DoctrinePlayerRepository::getAlias(), self::ALIAS, DoctrinePlayerRepository::getAlias()))
                    ->setParameter('championship', $championship)
                ;
                $qb->andWhere(sprintf('%s.uuid IS NULL', DoctrinePlayerRepository::getAlias()));
            });
    }
}
