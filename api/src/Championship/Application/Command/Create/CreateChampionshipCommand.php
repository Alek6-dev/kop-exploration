<?php

declare(strict_types=1);

namespace App\Championship\Application\Command\Create;

use App\Championship\Domain\Enum\ChampionshipNumberPlayerEnum;
use App\Championship\Domain\Enum\ChampionshipNumberRaceEnum;
use App\Season\Domain\Model\SeasonInterface;
use App\Shared\Application\Command\CommandInterface;
use App\User\Domain\Model\UserVisitorInterface;

/**
 * @implements CommandInterface<self>
 */
class CreateChampionshipCommand implements CommandInterface
{
    public function __construct(
        public SeasonInterface $season,
        public UserVisitorInterface $createdBy,
        public string $name,
        public bool $jokerEnabled,
        public ChampionshipNumberRaceEnum $championshipNumberRace,
        public ChampionshipNumberPlayerEnum $championshipNumberPlayer,
        public string $invitationCode,
    ) {
    }
}
