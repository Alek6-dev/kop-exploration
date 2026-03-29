<?php

declare(strict_types=1);

namespace App\Championship\Domain\Model;

use App\Championship\Domain\Enum\ChampionshipRaceStatusEnum;
use App\Race\Domain\Model\RaceInterface;
use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Stringifyable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;

interface ChampionshipRaceInterface extends Stringifyable, Idable, Uuidable, Timestampable
{
    public function getChampionship(): ?ChampionshipInterface;

    public function setChampionship(?ChampionshipInterface $championship): static;

    public function getRace(): ?RaceInterface;

    public function setRace(?RaceInterface $race): static;

    public function getStatus(): ?ChampionshipRaceStatusEnum;

    public function setStatus(ChampionshipRaceStatusEnum $status): static;
}
