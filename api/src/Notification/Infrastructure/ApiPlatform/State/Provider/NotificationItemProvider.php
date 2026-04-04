<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Notification\Infrastructure\ApiPlatform\Resource\NotificationResource;
use App\Notification\Infrastructure\Doctrine\Repository\DoctrineNotificationRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @implements ProviderInterface<NotificationResource>
 */
final readonly class NotificationItemProvider implements ProviderInterface
{
    public function __construct(
        private DoctrineNotificationRepository $notificationRepository,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): NotificationResource
    {
        /** @var string $uuid */
        $uuid = $uriVariables['uuid'];

        $notification = $this->notificationRepository->findByUuid($uuid);

        if (null === $notification) {
            throw new NotFoundHttpException(\sprintf('Notification "%s" not found.', $uuid));
        }

        return NotificationResource::fromEntity($notification, false);
    }
}
