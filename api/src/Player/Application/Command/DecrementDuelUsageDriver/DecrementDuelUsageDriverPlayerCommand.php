<?php

declare(strict_types=1);

namespace App\Player\Application\Command\DecrementDuelUsageDriver;

use App\Driver\Domain\Model\DriverInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class DecrementDuelUsageDriverPlayerCommand implements CommandInterface
{
    public function __construct(
        public DriverInterface $driver,
        public PlayerInterface $player,
    ) {
    }
}
