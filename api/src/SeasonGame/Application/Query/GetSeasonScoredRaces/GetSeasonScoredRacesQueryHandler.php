<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Query\GetSeasonScoredRaces;

use App\SeasonGame\Infrastructure\Doctrine\Repository\DoctrineSeasonGPStrategyRepository;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetSeasonScoredRacesQueryHandler
{
    public function __construct(
        private DoctrineSeasonGPStrategyRepository $repository,
    ) {
    }

    public function __invoke(GetSeasonScoredRacesQuery $query): array
    {
        return $this->repository->findScoredRacesForActiveSeason();
    }
}
