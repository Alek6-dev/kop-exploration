<?php

declare(strict_types=1);

namespace App\Shared\Application\Command\Email;

use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class SendEmailCommand implements CommandInterface
{
    /**
     * @param array<string, string> $context
     */
    public function __construct(
        public string $emailToAddress,
        public string $subject,
        public string $template,
        public array $context = [],
    ) {
    }
}
