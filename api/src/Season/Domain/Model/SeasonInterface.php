<?php

namespace App\Season\Domain\Model;

use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Stringifyable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use Doctrine\Common\Collections\Collection;

interface SeasonInterface extends Stringifyable, Idable, Uuidable, Timestampable
{
    public function getName(): ?string;

    public function setName(?string $name): static;

    public function isActive(): ?bool;

    public function setIsActive(?bool $isActive): static;

    public function getSeasonTeams(): ?Collection;

    public function getSeasonDrivers(): ?Collection;

    public function getSeasonActiveDrivers(): ?Collection;

    public function addSeasonTeam(SeasonTeamInterface $seasonTeam): static;

    public function removeSeasonTeam(SeasonTeamInterface $seasonTeam): void;

    public function getSeasonRaces(): ?Collection;

    public function addSeasonRace(SeasonRaceInterface $seasonRace): static;

    public function removeSeasonRace(SeasonRaceInterface $seasonRace): void;
}
