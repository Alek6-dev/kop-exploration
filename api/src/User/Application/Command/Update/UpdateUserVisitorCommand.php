<?php

declare(strict_types=1);

namespace App\User\Application\Command\Update;

use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class UpdateUserVisitorCommand implements CommandInterface
{
    public function __construct(
        public string $uuid,
        public ?string $email,
        public ?string $pseudo,
        public ?string $password,
        public ?string $image,
    ) {
    }
}
