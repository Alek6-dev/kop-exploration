<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Doctrine\Repository;

use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class DoctrineNotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    /**
     * Retourne les notifications visibles pour un user :
     * - isForAll = true OU user dans targets
     * - publishedAt non null et <= now
     * - expiresAt null ou > now
     * - pas de NotificationRead avec deletedAt non null pour ce user
     * Triées par publishedAt DESC.
     *
     * @return Notification[]
     */
    public function findVisibleForUser(UserVisitor $user): array
    {
        $now = new \DateTimeImmutable();

        return $this->createQueryBuilder('n')
            ->where('(n.isForAll = true OR :user MEMBER OF n.targets)')
            ->andWhere('n.publishedAt IS NOT NULL AND n.publishedAt <= :now')
            ->andWhere('n.expiresAt IS NULL OR n.expiresAt > :now')
            ->andWhere(
                'NOT EXISTS (SELECT nr FROM App\\Notification\\Infrastructure\\Doctrine\\Entity\\NotificationRead nr WHERE nr.notification = n AND nr.user = :user AND nr.deletedAt IS NOT NULL)'
            )
            ->orderBy('n.publishedAt', 'DESC')
            ->setParameter('user', $user)
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Retourne la première notification popup non lue et visible pour ce user,
     * ou null s'il n'y en a pas.
     */
    public function findPopupForUser(UserVisitor $user): ?Notification
    {
        $now = new \DateTimeImmutable();

        return $this->createQueryBuilder('n')
            ->where('(n.isForAll = true OR :user MEMBER OF n.targets)')
            ->andWhere('n.publishedAt IS NOT NULL AND n.publishedAt <= :now')
            ->andWhere('n.expiresAt IS NULL OR n.expiresAt > :now')
            ->andWhere('n.showAsPopup = true')
            ->andWhere(
                'NOT EXISTS (SELECT nr FROM App\\Notification\\Infrastructure\\Doctrine\\Entity\\NotificationRead nr WHERE nr.notification = n AND nr.user = :user AND nr.readAt IS NOT NULL)'
            )
            ->andWhere(
                'NOT EXISTS (SELECT nr2 FROM App\\Notification\\Infrastructure\\Doctrine\\Entity\\NotificationRead nr2 WHERE nr2.notification = n AND nr2.user = :user AND nr2.deletedAt IS NOT NULL)'
            )
            ->orderBy('n.publishedAt', 'ASC')
            ->setMaxResults(1)
            ->setParameter('user', $user)
            ->setParameter('now', $now)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByUuid(string $uuid): ?Notification
    {
        return $this->findOneBy(['uuid' => $uuid]);
    }
}
