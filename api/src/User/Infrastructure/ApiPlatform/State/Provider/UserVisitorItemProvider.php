<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Application\Query\Get\GetUserVisitorQuery;
use App\User\Infrastructure\ApiPlatform\Resource\UserResource;

/**
 * @implements ProviderInterface<UserResource>
 */
final readonly class UserVisitorItemProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?UserResource
    {
        /** @var string $uuid */
        $uuid = $uriVariables['uuid'];

        $model = $this->queryBus->ask(new GetUserVisitorQuery($uuid));

        return UserResource::fromModel($model);
    }
}
