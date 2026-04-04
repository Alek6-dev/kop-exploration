<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Notification\Application\Query\GetPopupNotificationForUser\GetPopupNotificationForUserQuery;
use App\Notification\Infrastructure\ApiPlatform\Resource\NotificationResource;
use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\Shared\Application\Query\QueryBusInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<array<NotificationResource>>
 */
final readonly class NotificationPopupProvider implements ProviderInterface
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

        /** @var ?Notification $notification */
        $notification = $this->queryBus->ask(new GetPopupNotificationForUserQuery($user));

        if (null === $notification) {
            return [];
        }

        return [NotificationResource::fromEntity($notification, false)];
    }
}
