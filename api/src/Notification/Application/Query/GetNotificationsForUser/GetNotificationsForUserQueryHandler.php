<?php

declare(strict_types=1);

namespace App\Notification\Application\Query\GetNotificationsForUser;

use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\Notification\Infrastructure\Doctrine\Repository\DoctrineNotificationReadRepository;
use App\Notification\Infrastructure\Doctrine\Repository\DoctrineNotificationRepository;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetNotificationsForUserQueryHandler
{
    public function __construct(
        private DoctrineNotificationRepository $notificationRepository,
        private DoctrineNotificationReadRepository $notificationReadRepository,
    ) {
    }

    /**
     * @return array<array{notification: Notification, isRead: bool}>
     */
    public function __invoke(GetNotificationsForUserQuery $query): array
    {
        $user = $query->user;

        $notifications = $this->notificationRepository->findVisibleForUser($user);
        $readMap = $this->notificationReadRepository->findReadMapForUser($user);

        $result = [];
        foreach ($notifications as $notification) {
            $notificationRead = $readMap[$notification->getId()] ?? null;
            $isRead = null !== $notificationRead && $notificationRead->isRead();

            $result[] = [
                'notification' => $notification,
                'isRead' => $isRead,
            ];
        }

        return $result;
    }
}
