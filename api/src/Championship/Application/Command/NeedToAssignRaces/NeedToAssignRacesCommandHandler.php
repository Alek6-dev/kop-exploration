<?php

declare(strict_types=1);

namespace App\Championship\Application\Command\NeedToAssignRaces;

use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class NeedToAssignRacesCommandHandler
{
    public function __invoke(NeedToAssignRacesCommand $command): ChampionshipInterface
    {
        return $command->championship
            ->setStatus(ChampionshipStatusEnum::NEED_TO_ASSIGN_RACES)
        ;
    }
}
