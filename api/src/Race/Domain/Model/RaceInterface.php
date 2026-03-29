<?php

namespace App\Race\Domain\Model;

use App\Race\Domain\Enum\CountryEnum;
use App\Season\Domain\Model\SeasonRaceInterface;
use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Stringifyable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;
use Doctrine\Common\Collections\Collection;

interface RaceInterface extends Stringifyable, Idable, Uuidable, Timestampable
{
    public function getName(): ?string;

    public function getCountry(): ?CountryEnum;

    public function setName(?string $name): static;

    public function setCountry(?CountryEnum $country): static;

    public function getSeasonRaces(): ?Collection;

    public function addSeasonRace(SeasonRaceInterface $seasonRace): static;

    public function removeSeasonRace(SeasonRaceInterface $seasonRace): void;
}
