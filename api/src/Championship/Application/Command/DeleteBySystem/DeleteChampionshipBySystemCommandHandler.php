<?php

declare(strict_types=1);

namespace App\Championship\Application\Command\DeleteBySystem;

use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class DeleteChampionshipBySystemCommandHandler
{
    public function __invoke(DeleteChampionshipBySystemCommand $command): ChampionshipInterface
    {
        $command->championship->setStatus(ChampionshipStatusEnum::CANCELLED_NOT_ENOUGH_RACES_LEFT);

        return $command->championship;
    }
}
