<?php

declare(strict_types=1);

namespace App\Championship\Application\Command\UpdateChampionshipRaceStatus;

use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Championship\Domain\Exception\ChampionshipRaceException;
use App\Championship\Domain\Model\ChampionshipRaceInterface;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class UpdateChampionshipRaceStatusCommandHandler
{
    public function __invoke(UpdateChampionshipRaceStatusCommand $command): ChampionshipRaceInterface
    {
        if (ChampionshipRaceStatusEnum::WAITING_RESULT === $command->status && ChampionshipRaceStatusEnum::ACTIVE !== $command->championshipRace->getStatus()) {
            throw ChampionshipRaceException::transitionStatusNotPossible($command->championshipRace->getStatus()->name, $command->status->name);
        } elseif (ChampionshipRaceStatusEnum::OVER === $command->status && ChampionshipRaceStatusEnum::RESULT_PROCESSED !== $command->championshipRace->getStatus()) {
            throw ChampionshipRaceException::transitionStatusNotPossible($command->championshipRace->getStatus()->name, $command->status->name);
        } elseif (ChampionshipRaceStatusEnum::RESULT_PROCESSED === $command->status && ChampionshipRaceStatusEnum::WAITING_RESULT !== $command->championshipRace->getStatus()) {
            throw ChampionshipRaceException::transitionStatusNotPossible($command->championshipRace->getStatus()->name, $command->status->name);
        }

        return $command->championshipRace
            ->setStatus($command->status)
        ;
    }
}
