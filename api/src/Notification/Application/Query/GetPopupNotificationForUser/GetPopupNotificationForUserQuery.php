<?php

declare(strict_types=1);

namespace App\Notification\Application\Query\GetPopupNotificationForUser;

use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use App\Shared\Application\Query\QueryInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;

/**
 * @implements QueryInterface<?Notification>
 */
final readonly class GetPopupNotificationForUserQuery implements QueryInterface
{
    public function __construct(
        public UserVisitor $user,
    ) {
    }
}
