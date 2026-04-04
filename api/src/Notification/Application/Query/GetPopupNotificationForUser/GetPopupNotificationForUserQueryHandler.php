<?php

declare(strict_types=1);

namespace App\Notification\Application\Query\GetPopupNotificationForUser;

use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\Notification\Infrastructure\Doctrine\Repository\DoctrineNotificationRepository;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class GetPopupNotificationForUserQueryHandler
{
    public function __construct(
        private DoctrineNotificationRepository $notificationRepository,
    ) {
    }

    public function __invoke(GetPopupNotificationForUserQuery $query): ?Notification
    {
        return $this->notificationRepository->findPopupForUser($query->user);
    }
}
