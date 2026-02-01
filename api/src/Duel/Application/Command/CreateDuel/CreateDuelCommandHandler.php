<?php

declare(strict_types=1);

namespace App\Duel\Application\Command\CreateDuel;

use App\Duel\Domain\Model\DuelInterface;
use App\Duel\Infrastructure\Doctrine\Entity\Duel;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class CreateDuelCommandHandler
{
    public function __invoke(CreateDuelCommand $command): DuelInterface
    {
        return (new Duel())
            ->setChampionship($command->championship)
            ->setRace($command->race)
            ->setPlayer1($command->player1)
            ->setPlayer2($command->player2)
            ->setPlayerDriver1($command->playerDriver1)
            ->setPlayerDriver2($command->playerDriver2)
        ;
    }
}
