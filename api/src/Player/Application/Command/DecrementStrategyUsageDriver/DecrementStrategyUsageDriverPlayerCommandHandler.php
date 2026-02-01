<?php

declare(strict_types=1);

namespace App\Player\Application\Command\DecrementStrategyUsageDriver;

use App\Player\Domain\Model\PlayerInterface;
use App\Shared\Application\Command\AsCommandHandler;

#[AsCommandHandler]
final readonly class DecrementStrategyUsageDriverPlayerCommandHandler
{
    public function __invoke(DecrementStrategyUsageDriverPlayerCommand $command): PlayerInterface
    {
        $player = $command->player;
        if ($command->driver === $command->player->getActiveSelectedDriver1()) {
            $player->setRemainingUsageDriver1($player->getRemainingUsageDriver1() - 1);
        } else {
            $player->setRemainingUsageDriver2($player->getRemainingUsageDriver2() - 1);
        }

        return $player;
    }
}
