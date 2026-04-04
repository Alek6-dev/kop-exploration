<?php

declare(strict_types=1);

namespace App\Notification\Application\Command\DeleteNotification;

use App\Shared\Application\Command\CommandInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;

/**
 * @implements CommandInterface<void>
 */
final readonly class DeleteNotificationCommand implements CommandInterface
{
    public function __construct(
        public string $notificationUuid,
        public UserVisitor $user,
    ) {
    }
}
