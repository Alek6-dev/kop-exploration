<?php

declare(strict_types=1);

namespace App\User\Application\Command\Delete;

use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class DeleteUserVisitorCommand implements CommandInterface
{
    public function __construct(
        public string $uuid,
    ) {
    }
}
