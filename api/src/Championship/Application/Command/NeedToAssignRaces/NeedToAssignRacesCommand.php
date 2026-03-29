<?php

declare(strict_types=1);

namespace App\Championship\Application\Command\NeedToAssignRaces;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class NeedToAssignRacesCommand implements CommandInterface
{
    public function __construct(
        public ChampionshipInterface $championship,
    ) {
    }
}
