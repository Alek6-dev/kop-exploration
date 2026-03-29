<?php

declare(strict_types=1);

namespace App\Championship\Application\Command\CreateAllChampionshipRaces;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class CreateAllChampionshipRacesCommand implements CommandInterface
{
    public function __construct(
        public ChampionshipInterface $championship,
    ) {
    }
}
