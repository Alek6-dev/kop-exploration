<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Command\SaveSeasonGPStrategy;

use App\SeasonGame\Infrastructure\Doctrine\Entity\SeasonParticipation;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class SaveSeasonGPStrategyCommand implements CommandInterface
{
    public function __construct(
        public SeasonParticipation $participation,
        public string $raceUuid,
        public string $driver1Uuid,
        public string $driver2Uuid,
        public string $teamUuid,
    ) {
    }
}
