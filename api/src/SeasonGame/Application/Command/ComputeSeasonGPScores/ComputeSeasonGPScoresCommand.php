<?php

declare(strict_types=1);

namespace App\SeasonGame\Application\Command\ComputeSeasonGPScores;

use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class ComputeSeasonGPScoresCommand implements CommandInterface
{
    public function __construct(
        public string $raceUuid,
    ) {
    }
}
