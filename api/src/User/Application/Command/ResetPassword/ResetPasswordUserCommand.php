<?php

declare(strict_types=1);

namespace App\User\Application\Command\ResetPassword;

use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class ResetPasswordUserCommand implements CommandInterface
{
    public function __construct(
        public string $token,
        public string $password,
    ) {
    }
}
