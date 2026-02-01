<?php

declare(strict_types=1);

namespace App\Bid\Domain\Model;

use App\Driver\Domain\Model\DriverInterface;

interface BettingRoundDriverInterface extends BettingRoundItemInterface
{
    public function setDriver(DriverInterface $driver): static;

    public function getDriver(): ?DriverInterface;
}
