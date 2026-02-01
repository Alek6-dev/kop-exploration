<?php

declare(strict_types=1);

namespace App\Championship\Application\Command\UpdateChampionshipRaceStatus;

use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Championship\Domain\Model\ChampionshipRaceInterface;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class UpdateChampionshipRaceStatusCommand implements CommandInterface
{
    public function __construct(
        public ChampionshipRaceInterface $championshipRace,
        public ChampionshipRaceStatusEnum $status,
    ) {
    }
}
