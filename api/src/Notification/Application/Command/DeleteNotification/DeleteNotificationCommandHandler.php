<?php

declare(strict_types=1);

namespace App\Notification\Application\Command\DeleteNotification;

use App\Notification\Infrastructure\Doctrine\Entity\NotificationRead;
use App\Notification\Infrastructure\Doctrine\Repository\DoctrineNotificationReadRepository;
use App\Notification\Infrastructure\Doctrine\Repository\DoctrineNotificationRepository;
use App\Shared\Application\Command\AsCommandHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsCommandHandler]
final readonly class DeleteNotificationCommandHandler
{
    public function __construct(
        private DoctrineNotificationRepository $notificationRepository,
        private DoctrineNotificationReadRepository $notificationReadRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(DeleteNotificationCommand $command): void
    {
        $notification = $this->notificationRepository->findByUuid($command->notificationUuid);

        if (null === $notification) {
            throw new NotFoundHttpException(\sprintf('Notification "%s" not found.', $command->notificationUuid));
        }

        $notificationRead = $this->notificationReadRepository->findForUserAndNotification($command->user, $notification);

        if (null === $notificationRead) {
            $notificationRead = (new NotificationRead())
                ->setUser($command->user)
                ->setNotification($notification)
            ;
            $this->entityManager->persist($notificationRead);
        }

        $notificationRead->setDeletedAt(new \DateTimeImmutable());

        $this->entityManager->flush();
    }
}
