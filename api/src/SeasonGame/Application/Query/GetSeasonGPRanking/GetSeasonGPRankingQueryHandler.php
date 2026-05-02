<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Query\GetSeasonGPRanking;

use App\SeasonGame\Infrastructure\Doctrine\Repository\DoctrineSeasonGPStrategyRepository;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetSeasonGPRankingQueryHandler
{
    public function __construct(
        private DoctrineSeasonGPStrategyRepository $repository,
    ) {
    }

    public function __invoke(GetSeasonGPRankingQuery $query): array
    {
        return $this->repository->findGPRanking($query->raceUuid);
    }
}
