<?php

declare(strict_types=1);

namespace App\User\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Application\Query\Get\GetUserVisitorByResetPasswordTokenQuery;
use App\User\Infrastructure\ApiPlatform\Resource\UserResource;

/**
 * @implements ProviderInterface<UserResource>
 */
final readonly class ResetPasswordUserItemProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?UserResource
    {
        /** @var string $token */
        $token = $uriVariables['token'];

        $model = $this->queryBus->ask(new GetUserVisitorByResetPasswordTokenQuery($token));

        return UserResource::fromModel($model);
    }
}
