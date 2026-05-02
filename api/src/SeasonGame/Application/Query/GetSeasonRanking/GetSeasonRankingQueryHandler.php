<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Query\GetSeasonRanking;

use App\SeasonGame\Infrastructure\Doctrine\Repository\DoctrineSeasonParticipationRepository;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetSeasonRankingQueryHandler
{
    public function __construct(
        private DoctrineSeasonParticipationRepository $repository,
    ) {
    }

    public function __invoke(GetSeasonRankingQuery $query): array
    {
        return $this->repository->findSeasonRanking();
    }
}
