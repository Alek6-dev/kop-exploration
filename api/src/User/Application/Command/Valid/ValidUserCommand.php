<?php

declare(strict_types=1);

namespace App\User\Application\Command\Valid;

use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class ValidUserCommand implements CommandInterface
{
    public function __construct(
        public string $token,
    ) {
    }
}
