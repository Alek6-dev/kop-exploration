<?php

declare(strict_types=1);

namespace App\Strategy\Application\Command\SelectDriver;

use App\Driver\Domain\Model\DriverInterface;
use App\Shared\Application\Command\CommandInterface;
use App\Strategy\Domain\Model\StrategyInterface;

/**
 * @implements CommandInterface<self>
 */
class SelectDriverCommand implements CommandInterface
{
    public function __construct(
        public StrategyInterface $strategy,
        public DriverInterface $driver,
    ) {
    }
}
