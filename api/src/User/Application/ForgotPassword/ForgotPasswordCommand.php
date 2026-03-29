<?php

declare(strict_types=1);

namespace App\User\Application\ForgotPassword;

use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class ForgotPasswordCommand implements CommandInterface
{
    public function __construct(
        public string $email,
    ) {
    }
}
