<?php

declare(strict_types=1);

namespace App\SeasonGame\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\SeasonGame\Application\Query\GetPreviousSeasons\GetPreviousSeasonsQuery;
use App\Season\Infrastructure\Doctrine\Entity\Season;
use App\Shared\Application\Query\QueryBusInterface;

final readonly class PreviousSeasonsProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $seasons = $this->queryBus->ask(new GetPreviousSeasonsQuery());

        return array_map(fn (Season $s) => [
            'uuid' => $s->getUuid(),
            'name' => $s->getName(),
        ], $seasons);
    }
}
