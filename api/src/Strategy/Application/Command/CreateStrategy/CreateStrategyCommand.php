<?php

declare(strict_types=1);

namespace App\Strategy\Application\Command\CreateStrategy;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Driver\Domain\Model\DriverInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class CreateStrategyCommand implements CommandInterface
{
    public function __construct(
        public ChampionshipInterface $championship,
        public RaceInterface $race,
        public PlayerInterface $player,
        public ?DriverInterface $driver = null,
    ) {
    }
}
