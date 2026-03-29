<?php

namespace App\Season\Domain\Model;

use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Stringifyable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use App\Team\Domain\Model\TeamInterface;

interface SeasonTeamInterface extends Stringifyable, Idable, Uuidable, Timestampable
{
    public function getTeam(): ?TeamInterface;

    public function setTeam(TeamInterface $team): static;

    public function getSeason(): SeasonInterface;

    public function setSeason(SeasonInterface $season): static;
}
