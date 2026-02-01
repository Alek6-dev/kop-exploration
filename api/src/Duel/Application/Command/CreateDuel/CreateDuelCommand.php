<?php

declare(strict_types=1);

namespace App\Duel\Application\Command\CreateDuel;

use App\Championship\Domain\Model\ChampionshipInterface;
use App\Driver\Domain\Model\DriverInterface;
use App\Player\Domain\Model\PlayerInterface;
use App\Race\Domain\Model\RaceInterface;
use App\Shared\Application\Command\CommandInterface;

/**
 * @implements CommandInterface<self>
 */
class CreateDuelCommand implements CommandInterface
{
    public function __construct(
        public ChampionshipInterface $championship,
        public RaceInterface $race,
        public PlayerInterface $player1,
        public PlayerInterface $player2,
        public ?DriverInterface $playerDriver1 = null,
        public ?DriverInterface $playerDriver2 = null,
    ) {
    }
}
