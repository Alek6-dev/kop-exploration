<?php

declare(strict_types=1);

namespace App\Duel\Application\Command\SelectDriver;

use App\Duel\Domain\Exception\DuelException;
use App\Duel\Domain\Model\DuelInterface;
use App\Player\Domain\Exception\PlayerException;
use App\Shared\Application\Command\AsCommandHandler;
use App\Strategy\Domain\Exception\StrategyException;

#[AsCommandHandler]
final readonly class SelectDriverCommandHandler
{
    public function __invoke(SelectDriverCommand $command): DuelInterface
    {
        $duel = $command->duel;
        $player = $command->player;
        $driver = $command->driver;

        $driver1 = $player->getActiveSelectedDriver1();
        $driver2 = $player->getActiveSelectedDriver2();

        if ($driver !== $driver1 && $driver !== $driver2) {
            StrategyException::driverNotFound($driver->getUuid());
        }

        if (($driver1 === $driver && 1 > $player->getRemainingDuelUsageDriver1())
            || ($driver2 === $driver && 1 > $player->getRemainingDuelUsageDriver2())) {
            throw DuelException::notEnoughUsageForDriver($player->getUuid(), $driver->getUuid());
        }

        if ($duel->getPlayer1() === $player) {
            $duel->setPlayerDriver1($driver);
        } elseif ($duel->getPlayer2() === $player) {
            $duel->setPlayerDriver2($driver);
        } else {
            throw PlayerException::notFound($player->getUuid());
        }

        return $duel;
    }
}
