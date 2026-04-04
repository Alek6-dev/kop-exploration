<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Notification\Application\Query\GetNotificationsForUser\GetNotificationsForUserQuery;
use App\Notification\Infrastructure\ApiPlatform\Resource\NotificationResource;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<array<NotificationResource>>
 */
final readonly class NotificationCollectionProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private Security $security,
    ) {
    }

    /**
     * @return array<NotificationResource>
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        /** @var UserVisitor $user */
        $user = $this->security->getUser();

        /** @var array<array{notification: \App\Notification\Infrastructure\Doctrine\Entity\Notification, isRead: bool}> $items */
        $items = $this->queryBus->ask(new GetNotificationsForUserQuery($user));

        $resources = [];
        foreach ($items as $item) {
            $resources[] = NotificationResource::fromEntity($item['notification'], $item['isRead']);
        }

        return $resources;
    }
}
