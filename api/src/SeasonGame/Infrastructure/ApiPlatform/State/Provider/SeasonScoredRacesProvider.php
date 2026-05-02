<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\SeasonGame\Application\Query\GetSeasonScoredRaces\GetSeasonScoredRacesQuery;
use App\Shared\Application\Query\QueryBusInterface;

final readonly class SeasonScoredRacesProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        return $this->queryBus->ask(new GetSeasonScoredRacesQuery());
    }
}
