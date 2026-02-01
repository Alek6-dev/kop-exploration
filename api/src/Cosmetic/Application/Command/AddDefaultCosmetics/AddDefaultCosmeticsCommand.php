<?php

declare(strict_types=1);

namespace App\Cosmetic\Application\Command\AddDefaultCosmetics;

use App\Shared\Application\Command\CommandInterface;
use App\User\Domain\Model\UserVisitorInterface;

/**
 * @implements CommandInterface<self>
 */
class AddDefaultCosmeticsCommand implements CommandInterface
{
    public function __construct(
        public UserVisitorInterface $user,
    ) {
    }
}
