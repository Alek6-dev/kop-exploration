<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Doctrine\Repository;

use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\Notification\Infrastructure\Doctrine\Entity\NotificationRead;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NotificationRead>
 */
class DoctrineNotificationReadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationRead::class);
    }

    public function findForUserAndNotification(UserVisitor $user, Notification $notification): ?NotificationRead
    {
        return $this->findOneBy([
            'user' => $user,
            'notification' => $notification,
        ]);
    }

    /**
     * Retourne un tableau indexé par notification ID → NotificationRead
     * pour toutes les NotificationRead du user (même supprimées).
     *
     * @return array<int, NotificationRead>
     */
    public function findReadMapForUser(UserVisitor $user): array
    {
        $records = $this->findBy(['user' => $user]);

        $map = [];
        foreach ($records as $record) {
            $map[$record->getNotification()->getId()] = $record;
        }

        return $map;
    }
}
