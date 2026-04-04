<?php

declare(strict_types=1);

namespace App\Notification\Application\Query\GetNotificationsForUser;

use App\Shared\Application\Query\QueryInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;

/**
 * @implements QueryInterface<array<array{notification: \App\Notification\Infrastructure\Doctrine\Entity\Notification, isRead: bool}>>
 */
final readonly class GetNotificationsForUserQuery implements QueryInterface
{
    public function __construct(
        public UserVisitor $user,
    ) {
    }
}
