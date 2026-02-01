<?php

declare(strict_types=1);

namespace App\Championship\Application\Command\Active;

use App\Championship\Domain\Enum\ChampionshipStatusEnum;
use App\Championship\Domain\Model\ChampionshipInterface;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class ActiveChampionshipCommandHandler
{
    public function __invoke(ActiveChampionshipCommand $command): ChampionshipInterface
    {
        return $command->championship
            ->setStatus(ChampionshipStatusEnum::ACTIVE)
        ;
    }
}
