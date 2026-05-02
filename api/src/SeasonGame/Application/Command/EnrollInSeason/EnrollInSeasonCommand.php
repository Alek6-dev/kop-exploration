<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Command\EnrollInSeason;

use App\Shared\Application\Command\CommandInterface;
use App\User\Infrastructure\Doctrine\Entity\UserVisitor;

/**
 * @implements CommandInterface<self>
 */
class EnrollInSeasonCommand implements CommandInterface
{
    public function __construct(
        public UserVisitor $user,
    ) {
    }
}
