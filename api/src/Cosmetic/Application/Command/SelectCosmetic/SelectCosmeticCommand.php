<?php

declare(strict_types=1);

namespace App\Cosmetic\Application\Command\SelectCosmetic;

use App\Cosmetic\Domain\Model\CosmeticInterface;
use App\Shared\Application\Command\CommandInterface;
use App\User\Domain\Model\UserVisitorInterface;

/**
 * @implements CommandInterface<self>
 */
class SelectCosmeticCommand implements CommandInterface
{
    public function __construct(
        public UserVisitorInterface $user,
        public CosmeticInterface $cosmetic,
    ) {
    }
}
