<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Query\GetSeasonParticipation;

use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonParticipation;
use App\SeasonGame\Infrastructure\Doctrine\Repository\DoctrineSeasonParticipationRepository;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetSeasonParticipationQueryHandler
{
    public function __construct(
        private DoctrineSeasonParticipationRepository $repository,
    ) {
    }

    public function __invoke(GetSeasonParticipationQuery $query): ?SeasonParticipation
    {
        return $this->repository->findByUserAndActiveSeason($query->user);
    }
}
