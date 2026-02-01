<?php

declare(strict_types=1);

namespace App\User\Application\Command\Create;

use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class CreateUserVisitorCommand implements CommandInterface
{
    public function __construct(
        public string $pseudo,
        public string $email,
        public string $password,
        public ?string $image
    ) {
    }
}
