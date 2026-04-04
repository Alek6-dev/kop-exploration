<?php

declare(strict_types=1);

namespace App\Notification\Application\Command\MarkAllNotificationsRead;

use App\Notification\Infrastructure\Doctrine\Entity\NotificationRead;
use App\Notification\Infrastructure\Doctrine\Repository\DoctrineNotificationReadRepository;
use App\Notification\Infrastructure\Doctrine\Repository\DoctrineNotificationRepository;
use App\Shared\Application\Command\AsCommandHandler;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommandHandler]
final readonly class MarkAllNotificationsReadCommandHandler
{
    public function __construct(
        private DoctrineNotificationRepository $notificationRepository,
        private DoctrineNotificationReadRepository $notificationReadRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(MarkAllNotificationsReadCommand $command): void
    {
        $user = $command->user;
        $now = new \DateTimeImmutable();

        $notifications = $this->notificationRepository->findVisibleForUser($user);
        $readMap = $this->notificationReadRepository->findReadMapForUser($user);

        foreach ($notifications as $notification) {
            $notificationRead = $readMap[$notification->getId()] ?? null;

            if (null === $notificationRead) {
                $notificationRead = (new NotificationRead())
                    ->setUser($user)
                    ->setNotification($notification)
                ;
                $this->entityManager->persist($notificationRead);
            }

            $notificationRead->setReadAt($now);
        }

        $this->entityManager->flush();
    }
}
