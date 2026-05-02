<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\Doctrine\Repository;

use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonGPStrategy;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonParticipation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\UuidV4;

class DoctrineSeasonGPStrategyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeasonGPStrategy::class);
    }

    public function findByParticipationAndRace(SeasonParticipation $participation, string $raceUuid): ?SeasonGPStrategy
    {
        return $this->createQueryBuilder('s')
            ->join('s.race', 'r')
            ->where('s.participation = :participation')
            ->andWhere('r.uuid = :raceUuid')
            ->setParameter('participation', $participation)
            ->setParameter('raceUuid', UuidV4::fromString($raceUuid)->toBinary())
            ->orderBy('s.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findGPRanking(string $raceUuid): array
    {
        return $this->createQueryBuilder('s')
            ->join('s.race', 'r')
            ->join('s.participation', 'p')
            ->join('p.user', 'u')
            ->where('r.uuid = :raceUuid')
            ->andWhere('s.points IS NOT NULL')
            ->setParameter('raceUuid', UuidV4::fromString($raceUuid)->toBinary())
            ->orderBy('s.points', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findUncomputedStrategiesForRace(string $raceUuid): array
    {
        return $this->createQueryBuilder('s')
            ->join('s.race', 'r')
            ->join('s.participation', 'p')
            ->join('p.user', 'u')
            ->where('r.uuid = :raceUuid')
            ->andWhere('s.points IS NULL')
            ->andWhere('s.driver1 IS NOT NULL')
            ->andWhere('s.driver2 IS NOT NULL')
            ->andWhere('s.team IS NOT NULL')
            ->setParameter('raceUuid', UuidV4::fromString($raceUuid)->toBinary())
            ->getQuery()
            ->getResult();
    }

    public function findScoredRacesForActiveSeason(): array
    {
        $strategies = $this->createQueryBuilder('s')
            ->join('s.race', 'r')
            ->join('s.participation', 'p')
            ->join('p.season', 'season')
            ->join(\App\Season\Infrastructure\Doctrine\Entity\SeasonRace::class, 'sr', 'WITH', 'sr.season = season AND sr.race = r')
            ->where('season.isActive = true')
            ->andWhere('s.points IS NOT NULL')
            ->orderBy('sr.date', 'DESC')
            ->getQuery()
            ->getResult();

        $seen = [];
        $races = [];
        foreach ($strategies as $strategy) {
            $uuid = $strategy->getRaceUuid();
            if (!isset($seen[$uuid])) {
                $seen[$uuid] = true;
                $races[] = [
                    'uuid' => $uuid,
                    'name' => $strategy->getRace()->getName(),
                ];
            }
        }

        return $races;
    }
}
