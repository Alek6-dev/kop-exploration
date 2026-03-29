<?php

namespace App\Season\Domain\Model;

use App\Race\Domain\Model\RaceInterface;
use App\Shared\Domain\Model\Behaviors\Idable;
use App\Shared\Domain\Model\Behaviors\Stringifyable;
use App\Shared\Domain\Model\Behaviors\Timestampable;
use App\Shared\Domain\Model\Behaviors\Uuidable;

interface SeasonRaceInterface extends Stringifyable, Idable, Uuidable, Timestampable
{
    public function getRace(): ?RaceInterface;

    public function setRace(RaceInterface $race): static;

    public function getSeason(): ?SeasonInterface;

    public function setSeason(SeasonInterface $season): static;

    public function getDate(): ?\DateTimeImmutable;

    public function setDate(?\DateTimeImmutable $date): static;

    public function getQualificationDate(): ?\DateTimeImmutable;

    public function setQualificationDate(?\DateTimeImmutable $qualificationDate): static;

    public function getSprintDate(): ?\DateTimeImmutable;

    public function setSprintDate(?\DateTimeImmutable $sprintDate): static;

    public function getLimitStrategyDate(): ?\DateTimeImmutable;

    public function setLimitStrategyDate(?\DateTimeImmutable $limitStrategyDate): static;

    public function getLaps(): ?int;

    public function setLaps(?int $laps): static;
}
