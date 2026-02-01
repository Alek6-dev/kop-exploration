<?php

declare(strict_types=1);

namespace App\Championship\Application\Command\Delete;

use App\Shared\Application\Command\CommandInterface;
use App\User\Domain\Model\UserVisitorInterface;

/**
 * @implements CommandInterface<self>
 */
class DeleteChampionshipCommand implements CommandInterface
{
    public function __construct(
        public string $uuid,
        public UserVisitorInterface $user,
        public bool $isSystem = false,
    ) {
    }
}
