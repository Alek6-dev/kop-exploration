<?php

declare(strict_types=1);

namespace App\Bid\Application\Command\AddBid;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Driver\Domain\Model\DriverInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Shared\Application\Command\CommandInterface;
use App\Team\Domain\Model\TeamInterface;

/**
 * @implements CommandInterface<self>
 */
class AddBidCommand implements CommandInterface
{
    public function __construct(
        public ChampionshipInterface $championship,
        public PlayerInterface $player,
        public ?DriverInterface $driver1 = null,
        public ?int $driver1BidAmount = null,
        public ?DriverInterface $driver2 = null,
        public ?int $driver2BidAmount = null,
        public ?TeamInterface $team = null,
        public ?int $teamBidAmount = null,
        public bool $isSetBySystem = false,
    ) {
    }
}
