<?php

declare(strict_types=1);

namespace App\Shared\Application\Command\Token;

use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class GenerateUniqueTokenCommand implements CommandInterface
{
    /**
     * @param array<string> $forbiddenToken
     */
    public function __construct(
        public int $length = 8,
        public array $forbiddenToken = [],
    ) {
    }
}
