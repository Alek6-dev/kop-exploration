<?php

declare(strict_types=1);

namespace App\Player\Application\Command\DecrementStrategyUsageDriver;

use App\Driver\Domain\Model\DriverInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class DecrementStrategyUsageDriverPlayerCommand implements CommandInterface
{
    public function __construct(
        public DriverInterface $driver,
        public PlayerInterface $player,
    ) {
    }
}
