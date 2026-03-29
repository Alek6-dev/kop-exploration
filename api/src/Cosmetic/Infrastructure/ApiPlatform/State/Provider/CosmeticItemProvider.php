<?php

declare(strict_types=1);

namespace App\Cosmetic\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Cosmetic\Application\Query\Get\GetCosmeticQuery;
use App\Cosmetic\Infrastructure\ApiPlatform\Resource\CosmeticResource;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Domain\Model\UserVisitorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Uuid;

readonly class CosmeticItemProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Security $security
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?CosmeticResource
    {
        /** @var string $uuid */
        $uuid = $uriVariables['uuid'];
        if (!Uuid::isValid($uuid)) {
            // TODO: custom
            throw new \Exception('Invalid UUid');
        }

        $model = $this->queryBus->ask(new GetCosmeticQuery($uuid));

        /** @var UserVisitorInterface $user */
        $user = $this->security->getUser();

        return CosmeticResource::fromModel($model, $user);
    }
}
