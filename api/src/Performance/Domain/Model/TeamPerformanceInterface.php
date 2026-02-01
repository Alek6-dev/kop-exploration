<?php

namespace App\Performance\Domain\Model;

use App\Race\Domain\Model\JoinRaceInterface;
use App\Result\Domain\Model\ResultInterface;
use App\Season\Domain\Model\JoinSeasonInterface;
use App\Team\Domain\Model\TeamInterface;

interface TeamPerformanceInterface extends PerformanceInterface, JoinSeasonInterface, JoinRaceInterface
{
    public function getTeam(): ?TeamInterface;

    public function setTeam(TeamInterface $team): static;

    public function getMultiplier(): int;

    public function setMultiplier(int $multiplier): static;

    public function getResult(): ?ResultInterface;

    public function setResult(ResultInterface $result): static;
}
