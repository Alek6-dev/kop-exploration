<?php

declare(strict_types=1);

namespace App\Parameter\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Parameter\Application\Query\Collection\GetAllParametersQuery;
use App\Parameter\Domain\Model\ParameterInterface;
use App\Parameter\Domain\Repository\ParameterRepositoryInterface;
use App\Parameter\Infrastructure\ApiPlatform\Resource\ParameterResource;
use App\Shared\Application\Query\QueryBusInterface;

/**
 * @implements ProviderInterface<array<ParameterResource>>
 */
final readonly class ParameterCollectionProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        /** @var ?ParameterRepositoryInterface $models */
        $models = $this->queryBus->ask(new GetAllParametersQuery());
        $resources = [];
        /** @var ParameterInterface $model */
        foreach ($models as $model) {
            $resources[] = ParameterResource::fromModel($model);
        }

        return $resources;
    }
}
