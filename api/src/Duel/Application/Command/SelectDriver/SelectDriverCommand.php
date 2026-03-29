<?php

declare(strict_types=1);

namespace App\Duel\Application\Command\SelectDriver;

use App\Driver\Domain\Model\DriverInterface;
use App\Duel\Domain\Model\DuelInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class SelectDriverCommand implements CommandInterface
{
    public function __construct(
        public DuelInterface $duel,
        public PlayerInterface $player,
        public DriverInterface $driver,
    ) {
    }
}
