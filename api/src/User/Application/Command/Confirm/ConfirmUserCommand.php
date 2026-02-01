<?php

declare(strict_types=1);

namespace App\User\Application\Command\Confirm;

use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class ConfirmUserCommand implements CommandInterface
{
    public function __construct(
        public string $uuid,
    ) {
    }
}
