<?php

declare(strict_types=1);

namespace App\Notification\Application\Command\MarkAllNotificationsRead;

use App\Shared\Application\Command\CommandInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;

/**
 * @implements CommandInterface<void>
 */
final readonly class MarkAllNotificationsReadCommand implements CommandInterface
{
    public function __construct(
        public UserVisitor $user,
    ) {
    }
}
