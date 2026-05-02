<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\Doctrine\Repository;

use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonParticipation;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineSeasonParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeasonParticipation::class);
    }

    public function findByUserAndActiveSeason(UserVisitor $user): ?SeasonParticipation
    {
        return $this->createQueryBuilder('sp')
            ->leftJoin('sp.roster', 'r')
            ->addSelect('r')
            ->join('sp.season', 's')
            ->where('sp.user = :user')
            ->andWhere('s.isActive = true')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findSeasonRanking(): array
    {
        return $this->createQueryBuilder('sp')
            ->join('sp.season', 's')
            ->join('sp.user', 'u')
            ->where('s.isActive = true')
            ->orderBy('sp.totalPoints', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
