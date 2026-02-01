<?php

declare(strict_types=1);

namespace App\Bid\Application\Command\CompleteBidBySystem;

use App\Bid\Domain\Model\BettingRoundInterface;
use App\Driver\Domain\Model\DriverInterface;
use App\Shared\Application\Command\CommandInterface;
use App\Team\Domain\Model\TeamInterface;

/**
 * @implements CommandInterface<self>
 */
class CompleteBidBySystemCommand implements CommandInterface
{
    public function __construct(
        public BettingRoundInterface $bettingRound,
        public DriverInterface|TeamInterface $item,
    ) {
    }
}
