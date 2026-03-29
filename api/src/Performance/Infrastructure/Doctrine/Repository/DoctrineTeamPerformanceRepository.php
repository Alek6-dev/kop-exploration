<?php

declare(strict_types=1);

namespace App\Performance\Infrastructure\Doctrine\Repository;

use App\Performance\Domain\Repository\TeamPerformanceRepositoryInterface;
use App\Performance\Infrastructure\Doctrine\Entity\TeamPerformance;
use App\Race\Domain\Model\RaceInterface;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Infrastructure\Doctrine\DoctrineRepository;
use App\Shared\Infrastructure\Doctrine\Repository\Trait\CrudRepositoryTrait;
use App\Team\Domain\Model\TeamInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class DoctrineTeamPerformanceRepository extends DoctrineRepository implements TeamPerformanceRepositoryInterface
{
    use CrudRepositoryTrait;
    private const string ENTITY_CLASS = TeamPerformance::class;
    private const string ALIAS = 'team_performance';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, self::ENTITY_CLASS, self::ALIAS);
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
            $qb->andWhere(sprintf('%s.race = :race', self::ALIAS))->setParameter('race', $race);
        });
    }

    public function withTeam(TeamInterface $team): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($team): void {
            $qb->andWhere(sprintf('%s.team = :team', self::ALIAS))->setParameter('team', $team);
        });
    }
}
