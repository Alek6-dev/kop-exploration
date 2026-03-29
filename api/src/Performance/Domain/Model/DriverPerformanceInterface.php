<?php

namespace App\Performance\Domain\Model;

use App\Driver\Domain\Model\DriverInterface;
use App\Performance\Domain\Enum\QualificationPositionPointEnum;
use App\Performance\Domain\Enum\RacePositionPointEnum;
use App\Performance\Domain\Enum\SprintPositionPointEnum;
use App\Race\Domain\Model\JoinRaceInterface;
use App\Result\Domain\Model\ResultInterface;
use App\Season\Domain\Model\JoinSeasonInterface;

interface DriverPerformanceInterface extends PerformanceInterface, JoinSeasonInterface, JoinRaceInterface, HasBonusInterface
{
    public function getDriver(): ?DriverInterface;

    public function setDriver(DriverInterface $driver): static;

    public function getQualificationPoints(): ?QualificationPositionPointEnum;

    public function setQualificationPoints(QualificationPositionPointEnum $qualificationPoints): static;

    public function getRacePoints(): ?RacePositionPointEnum;

    public function setRacePoints(RacePositionPointEnum $racePoints): static;

    public function getSprintPoints(): ?SprintPositionPointEnum;

    public function setSprintPoints(?SprintPositionPointEnum $sprintPoints): static;

    public function getPositionGain(): ?int;

    public function setPositionGain(int $positionGain): static;

    public function getSprintPosition(): ?string;

    public function setSprintPosition(?string $position): static;

    public function getQualificationPosition(): ?string;

    public function setQualificationPosition(string $position): static;

    public function getResult(): ?ResultInterface;

    public function setResult(ResultInterface $result): static;
}
