<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\SeasonGame\Application\Query\GetSeasonGPRanking\GetSeasonGPRankingQuery;
use App\SeasonGame\Infrastructure\ApiPlatform\Resource\SeasonGPStrategyResource;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonGPStrategy;
use App\Shared\Application\Query\QueryBusInterface;

final readonly class SeasonGPRankingProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $strategies = $this->queryBus->ask(new GetSeasonGPRankingQuery($uriVariables['raceUuid']));

        return array_map(
            fn (SeasonGPStrategy $s) => SeasonGPStrategyResource::fromModel($s),
            $strategies
        );
    }
}
