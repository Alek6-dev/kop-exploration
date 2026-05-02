<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Command\CreateSeasonRoster;

use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonParticipation;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class CreateSeasonRosterCommand implements CommandInterface
{
    public function __construct(
        public SeasonParticipation $participation,
        public array $driverUuids,
        public array $teamUuids,
    ) {
    }
}
