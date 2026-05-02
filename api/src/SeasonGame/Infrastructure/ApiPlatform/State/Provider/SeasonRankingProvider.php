<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\SeasonGame\Application\Query\GetSeasonRanking\GetSeasonRankingQuery;
use App\SeasonGame\Infrastructure\ApiPlatform\Resource\SeasonParticipationResource;
use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonParticipation;
use App\Shared\Application\Query\QueryBusInterface;

final readonly class SeasonRankingProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $participations = $this->queryBus->ask(new GetSeasonRankingQuery());

        return array_map(
            fn (SeasonParticipation $p) => SeasonParticipationResource::fromModel($p),
            $participations
        );
    }
}
