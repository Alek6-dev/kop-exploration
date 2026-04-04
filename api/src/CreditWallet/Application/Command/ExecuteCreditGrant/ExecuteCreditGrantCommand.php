<?php

declare(strict_types=1);

namespace App\CreditWallet\Application\Command\ExecuteCreditGrant;

use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
final readonly class ExecuteCreditGrantCommand implements CommandInterface
{
    public function __construct(
        public string $grantUuid,
    ) {
    }
}
