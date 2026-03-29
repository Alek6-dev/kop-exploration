<?php

declare(strict_types=1);

namespace App\Player\Application\Command\DecrementDuelUsageDriver;

use App\Player\Domain\Model\PlayerInterface;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class DecrementDuelUsageDriverPlayerCommandHandler
{
    public function __invoke(DecrementDuelUsageDriverPlayerCommand $command): PlayerInterface
    {
        $player = $command->player;
        if ($command->driver === $command->player->getActiveSelectedDriver1()) {
            $player->setRemainingDuelUsageDriver1($player->getRemainingDuelUsageDriver1() - 1);
        } else {
            $player->setRemainingDuelUsageDriver2($player->getRemainingDuelUsageDriver2() - 1);
        }

        return $player;
    }
}
